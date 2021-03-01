<?php

namespace Arcadia\Bundle\ValidatorBundle\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function getFailedValidationResponse($data, $constraints = null, $groups = null): ?Response
    {
        $violations = $this->validator->validate($data, $constraints, $groups);

        if ($violations->count() <= 0) {
            return null;
        }

        $messages = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $property = $violation->getPropertyPath();
            $messages[$property][] = $violation->getMessage();
        }

        $responseData = [
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => "Validation failed",
            'errors' => $messages,
        ];

        return new JsonResponse($responseData, Response::HTTP_BAD_REQUEST);
    }
}