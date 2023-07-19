<?php

namespace App\Backoffice\RequestDto\Category;

use App\Http\RequestResolver\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class StoreRequestDto implements RequestDtoInterface
{
    public function __construct(
        #[Assert\Length(max: 255)]
        public string $name,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->get('name'),
        );
    }
}
