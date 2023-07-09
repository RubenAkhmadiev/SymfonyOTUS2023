<?php

namespace App\GraphQL\Error;

use App\Exception\AppException;
use App\Exception\NotFoundException;
use GraphQL\Error\ClientAware;
use InvalidArgumentException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

class ClientAwareException extends AppException implements ClientAware
{
    public function __construct(private CategoryEnum $category, string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }

    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return $this->category->getValue();
    }

    public static function createFromValidationFailedException(ValidationFailedException $e): self
    {
        $message = 'Переданы некорректные данные.';
        foreach ($e->getViolations() as $v) {
            $message .= sprintf("\n%s: %s", $v->getPropertyPath(), $v->getMessage());
        }

        return new self(CategoryEnum::INVALID_ARGUMENT(), $message, $e);
    }

    public static function createFromInvalidArgumentException(InvalidArgumentException $e): self
    {
        return new self(CategoryEnum::INVALID_ARGUMENT(), $e->getMessage(), $e);
    }

    public static function createFromNotFoundException(NotFoundException $e): self
    {
        return new self(CategoryEnum::NOT_FOUND(), $e->getMessage(), $e);
    }

    public static function createAccessDenied(): self
    {
        return new self(CategoryEnum::ACCESS_DENIED());
    }
}