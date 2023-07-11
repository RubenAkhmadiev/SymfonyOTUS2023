<?php

namespace App\GraphQL\SchemaBuilder;

interface BuilderInterface
{
    public function build(): array;
}