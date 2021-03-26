<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackEndController extends AbstractController
{
    /**
     * @Route("/back_end", name="back_end")
     */
    public function index(): Response
    {
        return $this->render('back_end/index.html.twig', [
            'controller_name' => 'BackEndController',
        ]);
    }
}
