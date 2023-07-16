<?php

namespace App\GraphQL\Mutation;

use App\Entity\User;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUser implements MutationInterface
{
    public function __construct(
        private TypeRegistry $registry,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->bigInt())
            ->withDescription('Создание пользователя')
            ->withArguments(
                Argument::create('email', $this->registry->string())->withDescription('Email'),
                Argument::create('password', $this->registry->string())->withDescription('Пароль'),
            )
            ->withResolver(
                function (mixed $root, array $args): int {

                    $user = new User();
                    $user->setEmail($args['email']);

                    $hashedPassword = $this->passwordHasher->hashPassword(
                        $user,
                        $args['password']
                    );
                    $user->setPassword($hashedPassword);
                    $user->setCreationDate(new DateTime());

                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    return $user->getId();
                }
            )
            ->build();
    }
}
