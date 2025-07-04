<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QrzOscarsierraController extends AbstractController
{
    #[Route('/{_locale}/qrz-oscarsierra', name: 'qrz_oscarsierra_index')]
    public function index(string $_locale): Response
    {
        return $this->render('qrz_oscarsierra/index.html.twig', [
            'controller_name' => 'QrzOscarsierraController',
            'current_locale' => $_locale,
            'site' => 'qrz-oscarsierra',
        ]);
    }
}
