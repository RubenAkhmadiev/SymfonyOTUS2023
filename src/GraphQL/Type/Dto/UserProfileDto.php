<?php

namespace App\GraphQL\Type\Dto;

final class UserProfileDto implements TypeDtoInterface
{
    public function __construct(
        public int $id,
        public ?string $firstName = null,
        public ?string $secondName = null,
        public ?string $phone = null,
        public ?string $birthday = null,
        public ?array $addresses = null,
    ) {
    }

    public static function createEmpty(): self
    {
        return new self(0);
    }
}
