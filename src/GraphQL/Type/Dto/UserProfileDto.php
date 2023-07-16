<?php

namespace App\GraphQL\Type\Dto;

use DateTime;
use Doctrine\Common\Collections\Collection;

final class UserProfileDto implements TypeDtoInterface
{
    public function __construct(
        public int $id,
        public ?string $firstName = null,
        public ?string $secondName = null,
        public ?string $phone = null,
        public ?string $birthday = null,
        public ?Collection $addresses = null,
    ) {
    }

    public static function createEmpty(): self
    {
        return new self(0);
    }
}
