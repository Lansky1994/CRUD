<?php

namespace App\Controller;

use App\Model\GetCustomer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Model\AddCustomer;

/**
 * @Route ("/customer")
 */
class CustomerController extends AbstractController
{

    private $customerRepository;
    private $addCustomer;
    private $getCustomer;

    public function __construct(AddCustomer $addCustomer, CustomerRepository $customerRepository, GetCustomer $getCustomer)
    {
        $this->addCustomer = $addCustomer;
        $this->customerRepository = $customerRepository;
        $this->getCustomer = $getCustomer;
    }

    /**
     * @Route("/add", name="add_customer", methods={"POST"})
     */
    public function addCustomer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $response = $this->addCustomer->saveCustomer($data);
        return $response;
    }

    /**
     * @Route("/get/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getOneCustomer($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        $response = $this->getCustomer->getCust($customer);

        return $response;
    }

    /**
     * @Route("/get-all", name="get_all_customers", methods={"GET"})
     */
    public function getAllCustomers(): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $data = [];

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber(),
            ];
        }

        return new JsonResponse(['customers' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_customer", methods={"PUT"})
     */
    public function updateCustomer($id, Request $request): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $this->customerRepository->updateCustomer($customer, $data);

        return new JsonResponse(['status' => 'customer updated!']);
    }

    /**
     * @Route("/delete/{id}", name="delete_customer", methods={"DELETE"})
     */
    public function deleteCustomer($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        $this->customerRepository->removeCustomer($customer);

        return new JsonResponse(['status' => 'customer deleted']);
    }
}
