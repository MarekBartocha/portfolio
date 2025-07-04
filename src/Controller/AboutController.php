<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AboutController extends AbstractController
{
    #[Route('/{_locale}/about', name: 'about_index')]
    public function index(string $_locale): Response
    {
        return $this->render('about/index.html.twig', [
            'controller_name' => 'AboutController',
            'current_locale' => $_locale,
            'site' => 'about',
        ]);
    }
}
