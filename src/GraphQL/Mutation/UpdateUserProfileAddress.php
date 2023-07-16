<?php

namespace App\GraphQL\Mutation;

use App\ApiUser\CurrentUser;
use App\Entity\Address;
use App\Entity\UserProfile;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UpdateUserProfileAddress implements MutationInterface
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly EntityManagerInterface $entityManager,
        private readonly CurrentUser $currentUser,
        private readonly UserRepository $userRepository,
        private readonly AddressRepository $addressRepository,
    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->bigInt())
            ->withDescription('Обновление адреса пользователя')
            ->withArguments(
                Argument::create('id', $this->registry->string())->withDescription('ID адреса'),
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

                    if ($user->getProfile() === null) {
                        throw new UserNotFoundException('Профиль пользователя не найден');
                    }

                    $address = $this->addressRepository->find($args['id']);

                    if ($address === null) {
                        throw new UserNotFoundException('Адрес пользователя не найден');
                    }

                    $address->setCity($args['city']);
                    $address->setStreet($args['street']);
                    $address->setBuilding($args['building']);

                    $this->entityManager->persist($address);
                    $this->entityManager->flush();

                    return $address->getId();
                }
            )
            ->build();
    }
}
