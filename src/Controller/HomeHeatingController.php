<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeHeatingController extends AbstractController
{
    #[Route('/{_locale}/home-heating', name: 'home-heating_index')]
    public function index(string $_locale): Response
    {
        return $this->render('home-heating/index.html.twig', [
            'controller_name' => 'HomeHeatingController',
            'current_locale' => $_locale,
            'site' => 'home-heating',
        ]);
    }
}
