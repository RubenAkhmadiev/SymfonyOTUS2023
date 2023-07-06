<?php

namespace App\GraphQL\Mutation;

use GraphQL\Type\Definition\Type;

interface MutationInterface
{
    public function build(): array;
}
