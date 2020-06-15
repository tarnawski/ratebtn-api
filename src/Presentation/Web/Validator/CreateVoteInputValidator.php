<?php

declare(strict_types=1);

namespace App\Presentation\Web\Validator;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateVoteInputValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data): array
    {
        $errors = [];
        $violations = $this->transformViolationListToArray($this->validator->validate($data['url'] ?? null, [
            new NotBlank(),
            new Length(['min' => 5, 'max' => 255]),
        ]));
        empty($violations) ?: $errors['url'] = $violations;

        $violations = $this->transformViolationListToArray($this->validator->validate($data['value'] ?? null, [
            new NotBlank(),
            new GreaterThan(0),
            new LessThan(6),
        ]));
        empty($violations) ?: $errors['value'] = $violations;

        $violations = $this->transformViolationListToArray($this->validator->validate($data['fingerprint'] ?? null, [
            new NotBlank(),
            new Length(['min' => 5, 'max' => 255]),
        ]));
        empty($violations) ?: $errors['fingerprint'] = $violations;

        return $errors;
    }

    private function transformViolationListToArray(ConstraintViolationListInterface $violations): array
    {
        return array_map(fn(ConstraintViolation $violation) => $violation->getMessage(), iterator_to_array($violations));
    }
}