<?php

namespace App\Http\RequestResolver;

use Throwable;

class RequestDtoValidationException extends \Exception
{
    protected ?array $errors = null;

    /**
     * AppException constructor.
     * @param string $message
     * @param null|Throwable|array $errors
     */
    public function __construct(string $message, $errors = null)
    {
        if ($errors instanceof Throwable) {
            $this->errors = ['exception' => sprintf('%s: %s', \get_class($errors), $errors->getMessage())];
        }

        if (is_array($errors)) {
            $this->errors = $errors;
        }

        parent::__construct($message, 0);
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
