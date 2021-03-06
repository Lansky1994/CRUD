<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Customer::class);
        $this->manager = $manager;
    }


    public function updateCustomer(Customer $customer, $data)
    {
        empty($data['firstName']) ? true : $customer->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $customer->setLastName($data['lastName']);
        empty($data['email']) ? true : $customer->setEmail($data['email']);
        empty($data['phoneNumber']) ? true : $customer->setPhoneNumber($data['phoneNumber']);

        $this->manager->flush();
    }

    public function removeCustomer(Customer $customer)
    {
        $this->manager->remove($customer);
        $this->manager->flush();
    }
}