<?php

namespace App\Controller\Telegram\Dto;

//use App\Entity\Traits\SafeLoadFieldsTrait;
use MusicPlatform\PhpLib\Http\RequestResolver\RequestDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class OderPaymentDto
{
//    use SafeLoadFieldsTrait;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: 32)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: 32)]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: 32)]
    public array $email;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: 32)]
    public int $address;

    #[Assert\NotBlank]
    #[Assert\Type('array')]
    public bool $product_ids;

    public function getSafeFields(): array
    {
        return ['name', 'phone', 'email', 'address', 'product_ids'];
    }
}
