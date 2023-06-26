<?php

namespace App\Controller;

use App\GraphQL\Context;
use App\GraphQL\Type\MutationType;
use App\GraphQL\Type\QueryType;
use App\GraphQL\TypeRegistry;
use App\Http\Request\GraphQLRequest;
use App\Http\Response\JsonResponse;
use App\Logger\ExceptionWrapper;
use GraphQL\Error;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Server\ServerConfig;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Schema;
use GraphQL\Upload\UploadMiddleware;
use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(path: '/graphql', methods: ['POST'])]
class GraphQL
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(GraphQLRequest $request): JsonResponse
    {
        $psrFactory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psrFactory, $psrFactory, $psrFactory, $psrFactory);

        // Конвертирование Symfony Http Request -> PsrRequest

        $psrRequest = $psrHttpFactory->createRequest($request->getHttpRequest());
        $psrRequest = (new UploadMiddleware())->processRequest($psrRequest);

        $schema = new Schema(
            [
                'query' => $this->registry->nullableType(QueryType::class),
                'mutation' => $this->registry->nullableType(MutationType::class),
                'types' => $this->registry->getInterfacesImplementations(),
            ]
        );

        $config = ServerConfig::create()
            ->setSchema($schema)
            ->setContext(new Context($request->getHttpRequest()))
            ->setDebugFlag();

        $server = new StandardServer($config);

        // Выполнение GraphQL запроса

        $response = $server->executePsrRequest($psrRequest);

        // Пока поддерживаем выполнение только синхронно и
        // только 1 GraphQL запроса за 1 http запрос
        if (!($response instanceof ExecutionResult)) {
            throw new InvalidArgumentException('Выполнение нескольких запросов одновременно не поддерживается');
        }

        $responseData = $response
            ->setErrorFormatter(fn(Error\Error $e) => $this->formatError($e, $config, $request))
            ->toArray();

        return new JsonResponse($responseData);
    }

    /**
     * @param Error\Error $error
     * @param ServerConfig $config
     * @param GraphQLRequest $request
     * @return array
     * @throws Throwable
     */
    private function formatError(Error\Error $error, ServerConfig $config, GraphQLRequest $request): array
    {
        $formatted = Error\FormattedError::createFromException($error, $config->getDebugFlag());

        // Выбор уровня сообщения и его логирование
        $levelMethod = 'internal' === $formatted['extensions']['category']
            ? 'error'
            : 'warning';

        $exceptionWrapper = ExceptionWrapper::createFromException($error);
        $this->logger->$levelMethod(
            'GraphQL запрос: выполнен',
            [
                'request' => $request->getHttpRequest()->getContent(),
                'exception' => $exceptionWrapper->getExceptionClassName(),
                'message' => $exceptionWrapper->getMessage(),
                'violations' => $exceptionWrapper->getViolations(),
                'trace' => $exceptionWrapper->getTraceAsString(),
            ]
        );

        return $formatted;
    }
}
