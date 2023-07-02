<?php

namespace App\Controller\Telegram\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class OrderPaymentDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        public int $telegramId = null,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public string $firstName,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public string $secondName,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public string $phone,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public string $address,

        #[Assert\NotBlank]
        #[Assert\Type('array')]
        public array $itemIds,

        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        public int $sum,
    )
    {
    }

    public static function fromRequest(Request $request): self
    {

        return new self (
            telegramId: $request->request->get('user_id'),
            firstName: $request->request->get('first_name'),
            secondName: $request->request->get('second_name'),
            phone: $request->request->get('phone'),
            address: $request->request->get('address'),
            itemIds: explode(',', $request->request->get('item_ids')),
            sum: $request->request->get('sum'), // фиксированная сумма на момент оформления заказа
        );
    }
}
