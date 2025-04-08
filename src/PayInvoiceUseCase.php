<?php

namespace App;

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

    public function execute(int $id) {

        $invoice = $this->invoiceRepository->find($id);

        if(is_null($invoice)) {
            throw new \Exception("Invoice not found");
        }

        try {
            $invoice->pay();
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