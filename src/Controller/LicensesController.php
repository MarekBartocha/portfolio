<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LicensesController extends AbstractController
{
    #[Route('/{_locale}/licenses', name: 'licenses_index')]
    public function index(string $_locale): Response
    {
        return $this->render('licenses/index.html.twig', [
            'controller_name' => 'LicensesController',
            'current_locale' => $_locale,
            'site' => 'licenses',
        ]);
    }
}
