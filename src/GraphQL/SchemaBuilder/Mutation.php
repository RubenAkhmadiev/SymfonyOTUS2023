<?php

namespace App\GraphQL\SchemaBuilder;

use GraphQL\Type\Definition\Type;

final class Mutation implements BuilderInterface
{
    private ?string $description = null;
    private array $resolver = ['fn' => null];

    /** @var Argument[] */
    private array $arguments = [];

    private function __construct(private Type $type)
    {
    }

    public function withDescription(string $description): self
    {
        $self = clone $this;
        $self->description = $description;

        return $self;
    }

    public function withResolver(callable $resolver): self
    {
        $self = clone $this;
        $self->resolver['fn'] = $resolver;

        return $self;
    }

    public function withArguments(Argument ...$arguments): self
    {
        $self = clone $this;
        $self->arguments = $arguments;

        return $self;
    }

    public function build(): array
    {
        $result = [
            'type' => $this->type,
            'args' => [],
        ];

        if (null !== $this->description) {
            $result['description'] = $this->description;
        }

        if (null !== $this->resolver['fn']) {
            $result['resolve'] = $this->resolver['fn'];
        }

        foreach ($this->arguments as $argument) {
            $result['args'] += $argument->build();
        }

        return $result;
    }

    public static function create(Type $type): self
    {
        return new self($type);
    }
}