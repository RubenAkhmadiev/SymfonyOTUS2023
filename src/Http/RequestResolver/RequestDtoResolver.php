<?php

namespace App\Http\RequestResolver;

use Generator;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDtoResolver implements ArgumentValueResolverInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        try {
            $reflection = new ReflectionClass($argument->getType());

            return $reflection->implementsInterface(RequestDtoInterface::class);
        } catch (ReflectionException $ex) {
            return false;
        }
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        // Заполним DTO данными из запроса
        // DTO должен сам решать, как вынуть данные из запроса

        $class = $argument->getType();
        $dto = call_user_func([$class, 'fromRequest'], $request);

        // Теперь полученный объект можно провалидировать

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($dto);
        if ($errors->count() > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }

            throw new RequestDtoValidationException('Request is invalid', $messages);
        }

        // Если все ок, DTO можно
        // прокинуть в контроллер

        yield $dto;
    }
}
