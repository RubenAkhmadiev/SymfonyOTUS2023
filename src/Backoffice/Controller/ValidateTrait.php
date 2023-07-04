<?php

namespace App\Backoffice\Controller;

use App\Http\Request\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidateTrait
{
    protected ValidatorInterface $validator;

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    public function validate(Request $request, $requestDto): RequestDtoInterface
    {
        $dto = $requestDto::fromRequest($request);
        $errors = $this->validator->validate($dto);

        foreach ($errors as $error) {
            $request->getSession()->getFlashBag()->add('error', $error->getMessage());
        }

        return $dto;
    }
}
