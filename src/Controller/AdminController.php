<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/{_locale}/admin', name: 'admin_index')]
    public function index(string $_locale): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'current_locale' => $_locale,
            'site' => 'admin',
        ]);
    }
}
