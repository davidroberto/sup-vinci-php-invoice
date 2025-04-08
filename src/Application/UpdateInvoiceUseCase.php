<?php

namespace App\Application;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateInvoiceUseCase
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

    public function execute(int $id, string $title, float $price, string $description = null) {

        $invoice = $this->invoiceRepository->find($id);

        if(is_null($invoice)) {
            throw new \Exception("Invoice not found");
        }

        try {
            $invoice->update($title, $price, $description);
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $this->entityManager->persist($invoice);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception("Could not update invoice");
        }

    }
}