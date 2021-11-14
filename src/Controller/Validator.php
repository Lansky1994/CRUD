<?php

namespace App\Controller;

use App\Service\Message;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;


class Validator
{
    public function validatorCustomer($data): bool
    {
        $validator = Validation::createValidator();

        $constraint = new Assert\Collection([
            'firstName' => new Assert\Length(['min' => 1]),
            'lastName' => new Assert\Length(['min' => 1]),
            'email' => new Assert\Optional([
                new Assert\Email(),
                new Assert\NotBlank(),
            ]),
            'phoneNumber' => new Assert\Length(['min' => 12])
        ]);

        $errors = $validator->validate($data, $constraint);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $err = $error->getMessage();
                Message::getInstance()->addMessage($err, "E", '400');
            }
            return false;
        }
        return true;
    }

    public function validatorId($customer): bool
    {
        $validator = Validation::createValidator();

        $customerConstraint = new Assert\NotNull();

        $errors = $validator->validate($customer, $customerConstraint);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $err = $error->getMessage();
                Message::getInstance()->addMessage($err, "E", '400');
            }
            return false;
        }
        return true;
    }
}



