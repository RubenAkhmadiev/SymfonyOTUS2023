<?php

namespace App\Telegram\Controller\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class OrderPaymentDto
{
    public function __construct(
        #[Assert\Type('integer')]
        public ?int $telegramId = null,

        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public ?string $email = null,

        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public ?string $name = null,

        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public ?string $sername = null,

        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public ?string $phone = null,

        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public ?string $city = null,

        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public ?string $street = null,

        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public ?string $building = null,

        #[Assert\Type('array')]
        public ?array $itemIds = null,

        #[Assert\Type('integer')]
        public ?int $sum = null,
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        return new self (
            telegramId: $request->request->get('telegram_id', 3242348),
            email: $request->request->get('email'),
            name: $request->request->get('name'),
            sername: $request->request->get('sername'),
            phone: $request->request->get('phone'),
            city: $request->request->get('city'),
            street: $request->request->get('street'),
            building: $request->request->get('building'),
            itemIds: explode(',', $request->request->get('item_ids', '')),
            sum: (int) $request->request->get('sum', 100), // фиксированная сумма на момент оформления заказа
        );
    }
}
