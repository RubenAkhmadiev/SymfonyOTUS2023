<?php

namespace App\GraphQL\Mutation;

use GraphQL\Type\Definition\Type;

interface MutationInterface
{
    public function getName(): string;

    public function getType(): Type;

    public function getDescription(): ?string;

    public function getArgs(): array;

    public function getResolve(): callable;

    public function build(): array;
}
