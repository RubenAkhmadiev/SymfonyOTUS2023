<?php

namespace App\GraphQL\Type;

use App\Adapter\BackofficeAdapter;
use App\Adapter\CustomerAdapter;
use App\Adapter\Dto\CategoryDto;
use App\Adapter\Dto\PartnerDto;
use App\Adapter\Dto\ProductDto;
use App\ApiUser\CurrentUser;
use App\Backoffice\Entity\Category;
use App\Backoffice\Entity\Partner;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Field;
use App\GraphQL\SchemaBuilder\TypeConfig;
use App\GraphQL\Type\Object\CategoryType;
use App\GraphQL\Type\Object\CurrentUserType;
use App\GraphQL\Type\Object\PartnerType;
use App\GraphQL\Type\Object\ProductType;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

final class QueryType extends ObjectType
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly CurrentUser $currentUser,
        private readonly BackofficeAdapter $backofficeAdapter
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
                ->withArguments(
                    Argument::create('page', $this->registry->int())
                        ->withDescription('Кол-во результатов на страницу'),
                    Argument::create('perPage', $this->registry->int())
                        ->withDescription('Страница'),
                )
                ->withResolver(
                    function (mixed $root, array $args): array {
                        $categories = $this->backofficeAdapter->getCategories($args['perPage'], $args['page']);

                        return $categories['items'];
                    }
                ),

            Field::create('partners', $this->registry->nullableListOf($this->registry->type(PartnerType::class)))
                ->withArguments(
                    Argument::create('page', $this->registry->int())
                        ->withDescription('Кол-во результатов на страницу'),
                    Argument::create('perPage', $this->registry->int())
                        ->withDescription('Страница'),
                )
                ->withResolver(
                    function (mixed $root, array $args): array {
                        $partners = $this->backofficeAdapter->getPartners($args['perPage'], $args['page']);

                        return $partners['items'];
                    }
                ),

            Field::create('products', $this->registry->nullableListOf($this->registry->type(ProductType::class)))
                ->withArguments(
                    Argument::create('page', $this->registry->int())
                        ->withDescription('Кол-во результатов на страницу'),
                    Argument::create('perPage', $this->registry->int())
                        ->withDescription('Страница'),
                )
                ->withResolver(
                    function (mixed $root, array $args): array {
                        $products = $this->backofficeAdapter->getProducts($args['perPage'], $args['page']);

                        return $products['items'];
                    }
                ),

            Field::create('partner', $this->registry->type(PartnerType::class))
                ->withArguments(
                    Argument::create('partnerId', $this->registry->int())
                        ->withDescription('ID партнёра'),
                )
                ->withResolver(
                    function (mixed $root, array $args): PartnerDto {
                        return $this->backofficeAdapter->getPartner($args['partnerId']);
                    }
                ),

            Field::create('category', $this->registry->type(CategoryType::class))
                ->withArguments(
                    Argument::create('categoryId', $this->registry->int())
                        ->withDescription('ID категории'),
                )
                ->withResolver(
                    function (mixed $root, array $args): CategoryDto {
                        return $this->backofficeAdapter->getCategory($args['categoryId']);
                    }
                ),

            Field::create('product', $this->registry->type(ProductType::class))
                ->withArguments(
                    Argument::create('productId', $this->registry->int())
                        ->withDescription('ID продукта'),
                )
                ->withResolver(
                    function (mixed $root, array $args): ProductDto {
                        return $this->backofficeAdapter->getProduct($args['productId']);
                    }
                ),
        );

        parent::__construct($config->build());
    }
}
