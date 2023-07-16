<?php

declare(strict_types=1);

namespace App\Backoffice\Service;

use App\Backoffice\Entity\User;
use App\Backoffice\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(
        protected EntityManagerInterface $em,
    ) {
        $this->userRepository = $em->getRepository(User::class);
    }

    public function getAll(int $limit, int $page): array
    {
        $items = $this->userRepository->findBy(
            criteria: [],
            limit: $limit + 1,
            offset: $limit * $page,
        );

        return [
            'has_more' => count($items) > $limit,
            'items'    => array_slice($items, 0, $limit),
        ];
    }
}
