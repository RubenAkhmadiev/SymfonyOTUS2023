<?php

namespace App\GraphQL\Mutation;

abstract class Mutation implements MutationInterface
{
    public function build(): array
    {
        return [
            'description' => $this->getDescription(),
            'type'        => $this->getType(),
            'args'        => $this->getArgs(),
            'resolve'     => $this->getResolve()
        ];
    }
}
