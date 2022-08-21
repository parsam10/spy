<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewGameController extends AbstractController
{
    #[Route('/{_locale}/new/game', name: 'app_new_game', requirements: ['_locale' => 'en|fa'], defaults: ['_locale' => 'en'])]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->renderForm('new_game/index.html.twig', [
            'controller_name' => 'NewGameController',
            'user_role' => $user->getRoles()[0],
        ]);
    }
}
