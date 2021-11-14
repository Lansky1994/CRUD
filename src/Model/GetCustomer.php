<?php

namespace App\Model;

use App\Controller\Validator;
use App\Service\Message;
use Doctrine\ORM\EntityManagerInterface;



class GetCustomer
{
    private $manager;
    private $validator;

    public function __construct(EntityManagerInterface $manager, Validator $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }


    public function getCust($customer)
    {

        $valid = $this->validator->validatorId($customer);

        if(!$valid){
            return Message::getInstance()->getResponse();
        }

        $data = [
            'id' => $customer->getId(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'email' => $customer->getEmail(),
            'phoneNumber' => $customer->getPhoneNumber(),
        ];

        Message::getInstance()->addMessage($data, "S", '200');
        return Message::getInstance()->getResponse();
    }


}