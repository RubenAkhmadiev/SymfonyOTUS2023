<?php

namespace App\GraphQL\Type;

use App\Adapter\Dto\PartnerDto;
use App\ApiUser\CurrentUser;
use App\Backoffice\Entity\Category;
use App\Backoffice\Entity\Partner;
use App\Backoffice\Repository\CategoryRepository;
use App\Backoffice\Repository\PartnerRepository;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\Type\Dto\CategoryDto;
use App\GraphQL\Type\Object\CategoryType;
use App\GraphQL\Type\Object\CurrentUserType;
use App\GraphQL\Type\Object\PartnerType;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

final class QueryType extends ObjectType
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly CurrentUser $currentUser,
        private readonly CategoryRepository $categoryRepository,
        private readonly PartnerRepository $partnerRepository
    ) {
        $config = TypeConfig::create()->withFields(

            Field::create('echo', $this->registry->string())
                ->withArguments(
                    Argument::create('message', $this->registry->string())
                        ->withDescription('Тестовое сообщение')
                )
                ->withResolver(
                    function (mixed $root, array $args): string {
                        return $args['message'];
                    }
                ),

            Field::create('currentUser', $this->registry->nullableType(CurrentUserType::class))
                ->withResolver(
                    function (): CurrentUser {
                        if (!$this->currentUser->isAuthorized()) {
                            throw ClientAwareException::createAccessDenied();
                        }

                        return $this->currentUser;
                    }
                ),

            Field::create('categories', $this->registry->nullableListOf($this->registry->type(CategoryType::class)))
                ->withResolver(
                    function (): array {
                        $categories = $this->categoryRepository->findAll();

                        return array_map(
                            static fn(Category $category) => CategoryDto::fromEntity($category),
                           $categories,
                        );
                    }
                ),

            Field::create('partners', $this->registry->nullableListOf($this->registry->type(PartnerType::class)))
                ->withResolver(
                    function (): array {
                        $partners = $this->partnerRepository->findAll();

                        return array_map(
                            static fn(Partner $partner) => PartnerDto::fromEntity($partner),
                            $partners,
                        );
                    }
                ),
        );

        parent::__construct($config->build());
    }
}
