<?php

namespace App\Backoffice\RequestDto\User;

use App\Http\RequestResolver\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class IndexRequestDto implements RequestDtoInterface
{
    public function __construct(
        #[Assert\Positive]
        public int $limit,

        #[Assert\PositiveOrZero]
        public int $page,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            limit: $request->get('limit', 20),
            page: $request->get('page', 0),
        );
    }
}
