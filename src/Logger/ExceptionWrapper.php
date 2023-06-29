<?php

namespace App\Logger;

use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Throwable;

final class ExceptionWrapper
{
    private string $traceAsString;
    private string $message;
    private array $exceptions = [];
    private array $violations = [];

    private function __construct(Throwable $throwable)
    {
        $exception = $throwable;

        do {
            $this->traceAsString = $exception->getTraceAsString();
            $this->message = $exception->getMessage();
            $this->exceptions[] = $exception::class;

            if ($exception instanceof ValidationFailedException) {
                /** @var ConstraintViolationInterface $v */
                foreach ($exception->getViolations() as $v) {
                    $this->violations[$v->getPropertyPath()] = $v->getMessage();
                }
            }
        } while (null !== ($exception = $exception->getPrevious()));
    }

    public static function createFromException(Throwable $throwable): self
    {
        return new self($throwable);
    }

    public function getTraceAsString(): string
    {
        return $this->traceAsString;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getExceptionClassName(): string
    {
        return implode(' <- ', $this->exceptions);
    }

    public function getViolations(): array
    {
        return $this->violations;
    }
}