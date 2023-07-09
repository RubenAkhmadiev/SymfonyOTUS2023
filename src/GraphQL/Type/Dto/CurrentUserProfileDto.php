<?php

namespace App\GraphQL\Type\Dto;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

/** @psalm-immutable */
final class CurrentUserProfileDto implements TypeDtoInterface
{
    /**
     * @param int[] $serviceIds
     */
    public function __construct(

        #[PositiveOrZero]
        public int $id,

        public ?string $phone = null,
        public ?string $name = null,

        #[Email]
        public ?string $email = null,

        #[Positive]
        public ?int $age = null,

        public ?string $city = null,
        public array $serviceIds = [],
        public ?string $creationDate = null,
    ) {
    }

    public static function createEmpty(): self
    {
        return new self(0);
    }
}
