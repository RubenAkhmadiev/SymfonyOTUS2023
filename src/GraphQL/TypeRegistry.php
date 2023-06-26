<?php

namespace App\GraphQL;

use App\GraphQL\Type\Scalar\BigIntType;
use App\GraphQL\Type\Scalar\NullType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Psr\Container\ContainerInterface;
use GraphQL\Type\Definition\NullableType;
use stdClass;

final class TypeRegistry
{
    private array $types = [];

    public function __construct(private ContainerInterface $container)
    {
    }

    public function getEmptyObject(): stdClass
    {
        return $this->types[__METHOD__] ??= new stdClass();
    }

    public function getEmptyResolver(): callable
    {
        return $this->types[__METHOD__] ??= fn() => $this->getEmptyObject();
    }

    public function nullableType(string $type): Type|NullableType
    {
        return $this->types[$type] ??= $this->container->get($type);
    }

    public function type(string $type): NonNull
    {
        return Type::nonNull($this->nullableType($type));
    }

    public function getInterfacesImplementations(): array
    {
        return [];
    }

    // Aliases for some basic types

    public function listOf(Type $type): NonNull
    {
        return Type::nonNull(Type::listOf($type));
    }

    public function bigInt(): NonNull
    {
        return $this->type(BigIntType::class);
    }

    public function string(): NonNull
    {
        return Type::nonNull(Type::string());
    }

    public function int(): NonNull
    {
        return Type::nonNull(Type::int());
    }

    public function boolean(): NonNull
    {
        return Type::nonNull(Type::boolean());
    }

    public function float(): NonNull
    {
        return Type::nonNull(Type::float());
    }

    // Nullable basic types

    public function null(): ScalarType
    {
        return $this->nullableType(NullType::class);
    }

    public function nullableString(): ScalarType
    {
        return Type::string();
    }

    public function nullableBoolean(): ScalarType
    {
        return Type::boolean();
    }

    public function nullableInt(): ScalarType
    {
        return Type::int();
    }

    public function nullableBigInt(): Type
    {
        return $this->nullableType(BigIntType::class);
    }

    public function nullableFloat(): ScalarType
    {
        return Type::float();
    }

    public function nullableListOf(Type $type): ListOfType
    {
        return Type::listOf($type);
    }
}
