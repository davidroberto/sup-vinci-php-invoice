<?php

namespace App\Application;

use App\Repository\InvoiceRepository;

class GetInvoiceByIdUseCase
{

    private InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository) {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function execute(int $id) {
        $invoice = $this->invoiceRepository->find($id);


        if(is_null($invoice)) {
            throw new \Exception("Invoice not found");
        }

        return $invoice;
    }

}