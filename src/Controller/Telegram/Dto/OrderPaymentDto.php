<?php

namespace App\Controller\Telegram\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class OrderPaymentDto
{
    public function __construct(
        #[Assert\Type('integer')]
        public ?int $telegramId = null,

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
        public ?string $address = null,

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
            telegramId: $request->request->get('telegram_id', 833499252),
            name: $request->request->get('name'),
            sername: $request->request->get('sername'),
            phone: $request->request->get('phone'),
            address: $request->request->get('address'),
            itemIds: explode(',', $request->request->get('item_ids', '')),
            sum: (int) $request->request->get('sum', 100), // фиксированная сумма на момент оформления заказа
        );
    }
}
