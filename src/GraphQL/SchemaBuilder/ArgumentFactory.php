<?php

namespace App\GraphQL\SchemaBuilder;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\Type;

final class ArgumentFactory
{
    public function __construct(private TypeRegistry $registry)
    {
    }

    public function createFirst(): Argument
    {
        return Argument::create('first', $this->registry->int())
            ->withDescription('Кол-во результатов');
    }

    public function createAfter(): Argument
    {
        return Argument::create('after', $this->registry->nullableString())
            ->withDescription('Ссылка на курсор');
    }

    public function createBigIntId(): Argument
    {
        return Argument::create('id', $this->registry->bigInt())
            ->withDescription('Целочисленный идентификатор контента');
    }

    public function createSort(Type $type): Argument
    {
        return Argument::create('sort', $type)
            ->withDescription('Критерий сортировки');
    }
}
