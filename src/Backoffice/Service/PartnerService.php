<?php

declare(strict_types=1);

namespace App\Backoffice\Service;

use App\Backoffice\Entity\Partner;
use App\Backoffice\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;

class PartnerService
{
    protected PartnerRepository $partnerRepository;

    public function __construct(
        protected EntityManagerInterface $em,
    ) {
        $this->partnerRepository = $em->getRepository(Partner::class);
    }

    /**
     * @return array{has_more: bool, items: Partner[]}
     */
    public function getAll(int $limit, int $page): array
    {
        $items = $this->partnerRepository->findBy(
            criteria: [],
            limit: $limit + 1,
            offset: $limit * $page,
        );

        return [
            'has_more' => count($items) > $limit,
            'items'    => array_slice($items, 0, $limit),
        ];
    }

    public function getById(int $id)  {
        $item = $this->partnerRepository->find($id);

        if ($item === null) {
            throw new \Exception("Not found");
        }

        return $item;
    }
}
