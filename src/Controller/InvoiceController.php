<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    #[Route('/invoice', name: 'get_invoice', methods: ['GET'])]
    public function getInvoice() {

        $invoiceFakeFromDb = [
            "id" => 1,
            "title" => "Invoice 1",
            "description" => "Invoice description 1",
            "price" => 200,
            "status" => "pending",
        ];

       return $this->render('get-invoice.html.twig', [
           'invoice' => $invoiceFakeFromDb,
       ]);
    }

}