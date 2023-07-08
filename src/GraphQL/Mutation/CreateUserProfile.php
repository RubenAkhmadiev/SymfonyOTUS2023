<?php

namespace App\GraphQL\Mutation;

use App\Entity\UserProfile;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CreateUserProfile implements MutationInterface
{
    public function __construct(
        private TypeRegistry $registry,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->bigInt())
            ->withDescription('Создание профиля пользователя')
            ->withArguments(
                Argument::create('firstName', $this->registry->string())->withDescription('Имя пользователя'),
                Argument::create('secondName', $this->registry->string())->withDescription('Фамилия пользователя'),
                Argument::create('phone', $this->registry->string())->withDescription('Телефон пользователя'),
                Argument::create('birthday', $this->registry->string())->withDescription('День рождения пользователя'),
            )
            ->withResolver(
                function (mixed $root, array $args): int {

                    $userProfile = new UserProfile();
//        $userProfile->setUser();
                    $userProfile->setFirstName($args['firstName']);
                    $userProfile->setSecondName($args['secondName']);
                    $userProfile->setPhone($args['phone']);
                    $userProfile->setBirthDay(DateTime::createFromFormat("Y-m-d", $args['birthday']));

                    $this->entityManager->persist($userProfile);
                    $this->entityManager->flush();

                    return $userProfile->getId();

                }
            )
            ->build();
    }
}
