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
        public ?string $firstName = null,

        #[Assert\Type('string')]
        #[Assert\Length(max: 32)]
        public ?string $secondName = null,

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
            telegramId: $request->request->get('telegram_id'),
            firstName: $request->request->get('first_name'),
            secondName: $request->request->get('second_name'),
            phone: $request->request->get('phone'),
            address: $request->request->get('address'),
            itemIds: explode(',', $request->request->get('item_ids')),
            sum: $request->request->get('sum'), // фиксированная сумма на момент оформления заказа
        );
    }
}
