<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/{_locale}/contact', name: 'contact_index')]
    public function index(string $_locale): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'current_locale' => $_locale,
            'site' => 'contact',
        ]);
    }
}
