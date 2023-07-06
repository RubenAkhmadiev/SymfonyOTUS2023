<?php

namespace App\GraphQL\SchemaBuilder;

use GraphQL\Type\Definition\Type;

/** @psalm-immutable */
final class Argument implements BuilderInterface
{
    private ?string $description = null;
    private mixed $defaultValue = null;

    private function __construct(
        private string $name,
        private Type $type
    ) {
    }

    public function build(): array
    {
        $result = [$this->name => ['type' => $this->type]];

        if (null !== $this->description) {
            $result[$this->name]['description'] = $this->description;
        }

        if (null !== $this->defaultValue) {
            $result[$this->name]['defaultValue'] = $this->defaultValue;
        }

        return $result;
    }

    public function withDescription(string $description): self
    {
        $self = clone $this;
        $self->description = $description;

        return $self;
    }

    public function withDefaultValue(mixed $defaultValue): self
    {
        $self = clone $this;
        $self->defaultValue = $defaultValue;

        return $self;
    }

    public static function create(string $name, Type $type): self
    {
        return new self($name, $type);
    }
}