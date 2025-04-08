<?php

namespace App\Application;

use App\Repository\InvoiceRepository;
use Symfony\Component\DependencyInjection\Container;

class ListInvoicesUseCase
{

    private InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository) {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function execute() {
        $invoices = $this->invoiceRepository->findAll();

        return $invoices;
    }

}