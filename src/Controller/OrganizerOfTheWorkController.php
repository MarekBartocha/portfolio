<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrganizerOfTheWorkController extends AbstractController
{
    #[Route('/{_locale}/organizer-of-the-work', name: 'organizer-of-the-work_index')]
    public function index(string $_locale): Response
    {
        return $this->render('organizer-of-the-work/index.html.twig', [
            'controller_name' => 'OrganizerOfTheWorkController',
            'current_locale' => $_locale,
            'site' => 'organizer-of-the-work',
        ]);
    }
}
