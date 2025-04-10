<?php

namespace App\Controller;

use App\Application\CreateUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private $createUserUseCase;

    public function __construct(CreateUserUseCase $createUserUseCase) {
        $this->createUserUseCase = $createUserUseCase;
    }

    #[Route("/user/create", name: "user_create", methods: ["POST", "GET"])]
    public function createUser(Request $request): Response {

        if($request->getMethod() === "POST") {
            $email = $request->request->get("email");
            $password = $request->request->get("password");
            $role = $request->request->get("role");

            try {
                $this->createUserUseCase->execute($email, $password, $role);
                $this->addFlash("success", "User has been created");
            } catch (\Exception $exception) {
                $this->addFlash("error", $exception->getMessage());
            }
        }

        return $this->render('user/create.html.twig');
    }

}