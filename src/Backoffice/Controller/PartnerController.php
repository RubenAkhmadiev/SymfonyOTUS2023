<?php

namespace App\Backoffice\Controller;

use App\Backoffice\Entity\Partner;
use App\Backoffice\Service\PartnerService;
use App\Backoffice\RequestDto\Partner\{IndexRequestDto};
use App\Backoffice\View\Table;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PartnerController extends AbstractController
{
    use ValidateTrait;

    public function __construct(
        EntityManagerInterface   $em,
        ValidatorInterface       $validator,
        protected PartnerService $partnerService,
    ) {
        $this->setValidator($validator);
    }

    #[Route(
        path: '/admin/partners',
        name: 'app_backoffice_partners_index',
        methods: ['GET']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        /** @var IndexRequestDto $dto */
        $dto = $this->validate($request, IndexRequestDto::class);

        $result = $this->partnerService->getAll(
            limit: $dto->limit,
            page: $dto->page,
        );

        $body = array_map(static fn(Partner $partner) => [
            'id'   => $partner->getId(),
            'name' => $partner->getName(),
            'type' => $partner->getType()->getName(),
        ], $result['items']);

        return $this->render('backoffice/pages/partners/index.html.twig', [
            'partners' => (new Table())
                ->setHeader('ID')
                ->setHeader('Название')
                ->setHeader('Тип')
                ->setData($body)
                ->setPage($dto->page, $result['has_more'], $dto->limit)
        ]);
    }
}
