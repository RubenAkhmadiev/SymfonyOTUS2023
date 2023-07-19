<?php

namespace App\Backoffice\Controller;

use App\Http\RequestResolver\RequestDtoInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $hasErrors = false;

        foreach ($errors as $error) {
            $request->getSession()->getFlashBag()->add('error', $error->getMessage());
            $hasErrors = true;
        }

        if ($hasErrors) {
            $referer = $request->headers->get('referer');

            $redirectResponse = new RedirectResponse($referer);
            $redirectResponse->send();
       }

        return $dto;
    }
}
