<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class InvoiceController
{

    #[Route('/invoice', name: 'get_invoice', methods: ['GET'])]
    public function getInvoice() {
       dump("test"); die;
    }


}