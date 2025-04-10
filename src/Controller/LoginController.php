<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         $error = $authenticationUtils->getLastAuthenticationError();
         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();

          return $this->render('login/form.html.twig', [
               'last_username' => $lastUsername,
               'error'         => $error,
          ]);
      }
}
