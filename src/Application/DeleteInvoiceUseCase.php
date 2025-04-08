<?php

namespace App\Application;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteInvoiceUseCase
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

    public function execute(int $id) {

        $invoice = $this->invoiceRepository->find($id);

        if(is_null($invoice)) {
            throw new \Exception("Invoice not found");
        }

        try {
            $this->entityManager->remove($invoice);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            throw new \Exception("Failed to delete invoice, please try again");
        }

    }
}