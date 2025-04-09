<?php

namespace App;

use App\Entity\User;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class PayInvoiceUseCase
{
    private InvoiceRepository $invoiceRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->entityManager = $entityManager;
    }

    public function execute(int $id, User $user) {

        $invoice = $this->invoiceRepository->find($id);

        if(is_null($invoice)) {
            throw new \Exception("Invoice not found");
        }

        try {
            $invoice->pay($user);
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($invoice);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception("Could not pay invoice");
        }

    }

}