<?php

namespace App\Application;

use App\Entity\Invoice;
use App\Entity\User;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateInvoiceUseCase
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function execute(string $title, float $price, string $description, User $user) {

        try {
            $invoice = new Invoice($title, $price, $user, $description);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($invoice);
            $this->entityManager->flush();


        } catch (\Exception $exception) {
            throw new \Exception("Cannot create invoice. Please try again later");
        }
    }

}