<?php

namespace App\GraphQL\SchemaBuilder;

/** @psalm-immutable */
final class TypeConfig implements BuilderInterface
{
    /** @var Field[] */
    private array $fields = [];

    private function __construct()
    {
    }

    public function build(): array
    {
        $result = ['fields' => []];

        foreach ($this->fields as $field) {
            if ($field->isAvailable()) {
                $result['fields'][] = $field->build();
            }
        }

        return $result;
    }

    public function withFields(Field ...$fields): self
    {
        $self = clone $this;
        $self->fields = $fields;

        return $self;
    }

    public static function create(): self
    {
        return new self();
    }
}