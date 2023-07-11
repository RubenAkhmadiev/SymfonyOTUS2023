<?php

namespace App\GraphQL\Mutation;

use App\ApiUser\CurrentUser;
use App\Entity\Address;
use App\Entity\UserProfile;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class CreateUserProfileAddress implements MutationInterface
{
    public function __construct(
        private TypeRegistry $registry,
        private EntityManagerInterface $entityManager,
        private CurrentUser $currentUser,
        private UserRepository $userRepository,
    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->bigInt())
            ->withDescription('Создание адреса пользователя')
            ->withArguments(
                Argument::create('city', $this->registry->string())->withDescription('Город'),
                Argument::create('street', $this->registry->string())->withDescription('Улица'),
                Argument::create('building', $this->registry->string())->withDescription('Дом'),
            )
            ->withResolver(
                function (mixed $root, array $args): int {

                    if (!$this->currentUser->isAuthorized()) {
                        throw ClientAwareException::createAccessDenied();
                    }

                    $user = $this->userRepository->find($this->currentUser->getUserId());
                    if ($user === null) {
                        throw new UserNotFoundException('Данный пользователь не найден');
                    }

                    $address = new Address();
                    $address->setCity($args['city']);
                    $address->setStreet($args['street']);
                    $address->setBuilding($args['building']);
                    $address->setProfile($user->getProfile());

                    $this->entityManager->persist($address);
                    $this->entityManager->flush();

                    return $address->getId();
                }
            )
            ->build();
    }
}
