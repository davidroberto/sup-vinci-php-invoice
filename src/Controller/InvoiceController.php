<?php

namespace App\Controller;

use App\Application\CreateInvoiceUseCase;
use App\Application\DeleteInvoiceUseCase;
use App\Application\GetInvoiceByIdUseCase;
use App\Application\ListInvoicesUseCase;
use App\Application\UpdateInvoiceUseCase;
use App\PayInvoiceUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{

    private $createInvoiceUseCase;
    private $listInvoicesUseCase;
    private $getInvoiceByIdUseCase;
    private $deleteInvoiceUseCase;
    private $updateInvoiceUseCase;
    private $payInvoiceUseCase;


    public function __construct(
        CreateInvoiceUseCase $createInvoiceUseCase,
        ListInvoicesUseCase $listInvoicesUseCase,
        GetInvoiceByIdUseCase $getInvoiceByIdUseCase,
        DeleteInvoiceUseCase $deleteInvoiceUseCase,
        UpdateInvoiceUseCase $updateInvoiceUseCase,
        PayInvoiceUseCase $payInvoiceUseCase
    ){
        $this->createInvoiceUseCase = $createInvoiceUseCase;
        $this->listInvoicesUseCase = $listInvoicesUseCase;
        $this->getInvoiceByIdUseCase = $getInvoiceByIdUseCase;
        $this->deleteInvoiceUseCase = $deleteInvoiceUseCase;
        $this->updateInvoiceUseCase = $updateInvoiceUseCase;
        $this->payInvoiceUseCase = $payInvoiceUseCase;
    }


    #[Route('/create-invoice', name: 'create_invoice', methods: ['GET', 'POST'])]
    public function createInvoice(Request $request): Response {

        if ($request->getMethod() === 'POST') {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $price = (float)$request->request->get('price');
            $user = $this->getUser();


            if (!$title || !$price) {
                $this->addFlash("error", "Le titre et le prix doivent être renseignés");
                return $this->render('invoice/create.html.twig', []);
            }

            try {
                $this->createInvoiceUseCase->execute($title, $price, $description, $user);

                $this->addFlash("success", "Invoice created");
            } catch(\Exception $e) {
                $this->addFlash("error", $e->getMessage());
            }

        }
        return $this->render('invoice/create.html.twig', []);
    }

    #[Route('list-invoices', name: 'list_invoices', methods: ['GET'])]
    public function listInvoices(): Response {

        $invoices = $this->listInvoicesUseCase->execute();

        return $this->render('invoice/list.html.twig', [
            'invoices' => $invoices
        ]);
    }

    #[Route('get-invoice/{id}', name: 'get_invoice_by_id', methods: ['GET'])]
    public function getInvoiceById(int $id): Response {

        try {
            $invoice = $this->getInvoiceByIdUseCase->execute($id);
        } catch(\Exception $e) {
            return $this->render('404.html.twig', []);
        }

        return $this->render('invoice/show-invoice.html.twig', [
            'invoice' => $invoice
        ]);
    }

    #[Route('delete-invoice/{id}', name: 'delete_invoice', methods: ['POST'])]
    public function removeInvoice(int $id): Response {
        try {
            $invoice = $this->deleteInvoiceUseCase->execute($id);
        } catch(\Exception $e) {
            // gérér plus finement les exceptions: le use case peut aussi soulever
            // une erreur si BDD crash
            return $this->render('404.html.twig', []);
        }

        $this->addFlash("success", "Delete OK");

        return $this->redirectToRoute('list_invoices');
    }


    #[Route('update-invoice/{id}', name: 'update_invoice', methods: ['GET', 'POST'])]
    public function updateInvoice(int $id, Request $request): Response {

        if ($request->getMethod() === 'POST') {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $price = (float)$request->request->get('price');

            if (!$title || !$price) {
                $this->addFlash("error", "Le titre et le prix doivent être renseignés");
            }

            try {
                $this->updateInvoiceUseCase->execute($id, $title,$price, $description);
                $this->addFlash("success", "Update OK");

                return $this->redirectToRoute('list_invoices');

            } catch(\Exception $e) {
                $this->addFlash("error", $e->getMessage());
            }

        }

        try {
            $invoice = $this->getInvoiceByIdUseCase->execute($id);
        } catch(\Exception $e) {
            return $this->render('404.html.twig', []);
        }


        return $this->render('invoice/update-invoice.html.twig', [
            'invoice' => $invoice
        ]);

    }


    #[Route('pay/invoice/{id}', name: 'pay_invoice', methods: ['POST'])]
    public function payInvoice(int $id, Request $request): Response {

        $user = $this->getUser();
        try {
            $this->payInvoiceUseCase->execute($id, $user);
        } catch(\Exception $e) {
            return $this->render('404.html.twig', []);
        }

        $this->addFlash("success", "Pay OK");
        return $this->redirectToRoute('list_invoices');
    }



}