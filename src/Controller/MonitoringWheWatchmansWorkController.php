<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MonitoringWheWatchmansWorkController extends AbstractController
{
    #[Route('/{_locale}/monitoring-whe-watchmans-work', name: 'monitoring-whe-watchmans-work_index')]
    public function index(string $_locale): Response
    {
        return $this->render('monitoring-whe-watchmans-work/index.html.twig', [
            'controller_name' => 'MonitoringWheWatchmansWorkController',
            'current_locale' => $_locale,
            'site' => 'monitoring-whe-watchmans-work',
        ]);
    }
}
