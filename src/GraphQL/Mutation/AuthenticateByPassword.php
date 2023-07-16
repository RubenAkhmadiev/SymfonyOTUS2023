<?php

namespace App\GraphQL\Mutation;

use App\Entity\User;
use App\GraphQL\Error\CategoryEnum;
use App\GraphQL\Error\ClientAwareException;
use App\GraphQL\SchemaBuilder\Argument;
use App\GraphQL\SchemaBuilder\Mutation;
use App\GraphQL\TypeRegistry;
use App\Repository\UserRepository;
use App\Security\TokenManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class AuthenticateByPassword implements MutationInterface
{
    public function __construct(
        private readonly TypeRegistry $registry,
        private readonly TokenManager $tokenManager,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher

    ) {
    }

    public function build(): array
    {
        return Mutation::create($this->registry->string())
            ->withDescription('Аутентификация пользователя по email и паролю')
            ->withArguments(
                Argument::create('email', $this->registry->string()),
                Argument::create('password', $this->registry->string()),
            )
            ->withResolver(
                function (mixed $root, array $args): string {
                    try {
                        /* @var UserRepository $userRepository */
                        $userRepository = $this->entityManager->getRepository(User::class);

                        $user = $userRepository->findOneByEmail($args['email']);

                        if (null === $user || !$this->passwordHasher->isPasswordValid($user, $args['password'])) {
                            throw new ClientAwareException(
                                CategoryEnum::INVALID_ARGUMENT,
                                'Указано неверный email или пароль'
                            );
                        }
                    } catch (ValidationFailedException $e) {
                        throw ClientAwareException::createFromValidationFailedException($e);
                    }

                    return $this->tokenManager->createToken($user->getId());
                }
            )
            ->build();
    }
}
