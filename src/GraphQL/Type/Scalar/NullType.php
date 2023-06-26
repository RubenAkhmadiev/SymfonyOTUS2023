<?php


namespace App\GraphQL\Type\Scalar;

use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class NullType extends ScalarType
{
    public function serialize($value)
    {
        return null;
    }

    public function parseValue($value)
    {
        return null;
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        return null;
    }
}
