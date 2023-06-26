<?php

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

final class GraphQLRequest implements RequestDtoInterface
{
    public function __construct(
        private readonly ?string $query,
        private readonly array $variables,
        private readonly Request $request,
    ) {
    }

    public static function fromRequest(Request $request): RequestDtoInterface
    {
        if ('json' === $request->getContentType()) {
            $decoded = json_decode((string)$request->getContent(), true, 512, JSON_THROW_ON_ERROR) ?? [];
            $request->request->set('variables', $decoded['variables'] ?? []);
            $request->request->set('query', $decoded['query'] ?? '');
        }

        return new self(
            $request->request->get('query'),
            (array)$request->request->get('variables'),
            $request,
        );
    }

    public function getQuery(): string
    {
        return (string)$this->query;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getHttpRequest(): Request
    {
        return $this->request;
    }
}
