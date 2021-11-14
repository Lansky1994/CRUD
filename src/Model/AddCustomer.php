<?php

namespace App\Model;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Service\Message;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Validator;
use Doctrine\DBAL;

class AddCustomer
{
    private $customerRepository;
    private $manager;
    private $validator;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $manager, Validator $validator)
    {
        $this->customerRepository = $customerRepository;
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function saveCustomer($data)
    {
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $phoneNumber = $data['phoneNumber'];

        $valid = $this->validator->validatorCustomer($data);


        if(!$valid){
            return Message::getInstance()->getResponse();
        }

        $newCustomer = new Customer();

        $newCustomer
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPhoneNumber($phoneNumber);

        try {
            $this->manager->persist($newCustomer);
            $this->manager->flush();
        }catch (DBAL\Exception $exception){
            Message::getInstance()->addMessage($exception->getMessage(), "E", '400');
            return Message::getInstance()->getResponse();
        }
        Message::getInstance()->addMessage($data, "S", '200');
        return Message::getInstance()->getResponse();
    }
}