<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Adapter\Dto\PartnerDto;
use App\Backoffice\Entity\Partner;
use App\Backoffice\Service\PartnerService;

final class BackofficeAdapter
{
    public function __construct(
        protected PartnerService $partnerService,
    ) {
    }

    /**
     * @return array{has_more: bool, items: PartnerDto[]}
     */
    public function getPartners(
        int $limit = 20,
        int $page = 0,
        array $filters = [],
        array $orderBy = [],
    ): array {
        $result = $this->partnerService->getAll(
            limit: $limit,
            page: $page,
        );

        return [
            'has_more' => $result['has_more'],
            'items'    => array_map(
                static fn(Partner $partnerEntity) => PartnerDto::fromEntity($partnerEntity),
                $result['items'],
            ),
        ];
    }
}
