<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/{entry}', name: 'app_entry', requirements: ['entry' => '^(?!api).+'], defaults: ['entry' => null])]
    public function app() : Response
    {
        return $this->render('base.html.twig');
    }
}
