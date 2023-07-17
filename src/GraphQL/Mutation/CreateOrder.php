<?php

namespace App\GraphQL\Mutation;

use App\ApiUser\CurrentUser;
use App\Entity\Order;
use App\Enum\OrderStatusEnum;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Validator\Constraints\Date;

class CreateOrder implements MutationInterface
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
            ->withDescription('Создание заказа')
            ->withResolver(
                function (): int {

                    if (!$this->currentUser->isAuthorized()) {
                        throw ClientAwareException::createAccessDenied();
                    }

                    $user = $this->userRepository->find($this->currentUser->getUserId());
                    if ($user === null) {
                        throw new UserNotFoundException('Данный пользователь не найден');
                    }

                    $order = new Order();
                    $order->setUser($user);
                    $order->setNumber(random_int(1, 100000));
                    $order->setStatus(OrderStatusEnum::NEW->value);
                    $order->setCreationDate(new \DateTime());
                    $order->setSum(0);

                    $user->addOrder($order);

                    $this->entityManager->persist($order);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    return $order->getId();
                }
            )
            ->build();
    }
}
