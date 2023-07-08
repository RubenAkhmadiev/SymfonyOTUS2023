<?php

namespace App\GraphQL\SchemaBuilder;

use GraphQL\Type\Definition\Type;

final class Field implements BuilderInterface
{
    private ?string $description = null;
    private ?string $deprecationReason = null;
    private array $resolver = ['fn' => null];
    private bool $isAvailable = true;

    /** @var Argument[] */
    private array $arguments = [];


    private function __construct(
        private string $name,
        private Type $type
    ) {
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function build(): array
    {
        $result = [
            'name' => $this->name,
            'type' => $this->type,
            'args' => [],
        ];

        if (null !== $this->description) {
            $result['description'] = $this->description;
        }

        if (null !== $this->deprecationReason) {
            $result['deprecationReason'] = $this->deprecationReason;
        }


        if (null !== $this->resolver['fn']) {
            $result['resolve'] = $this->resolver['fn'];
        }

        foreach ($this->arguments as $argument) {
            $result['args'] += $argument->build();
        }

        return $result;
    }

    public function withAvailability(bool $isAvailable): self
    {
        $self = clone $this;
        $self->isAvailable = $isAvailable;

        return $self;
    }

    public function withDescription(string $description): self
    {
        $self = clone $this;
        $self->description = $description;

        return $self;
    }

    public function withDeprecationReason(string $deprecationReason): self
    {
        $self = clone $this;
        $self->deprecationReason = $deprecationReason;

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

    public static function create(string $name, Type $type): self
    {
        return new self($name, $type);
    }
}
