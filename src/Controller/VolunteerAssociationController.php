<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VolunteerAssociationController extends AbstractController
{
    #[Route('/{_locale}/volunteer-association', name: 'volunteer-association_index')]
    public function index(string $_locale): Response
    {
        return $this->render('volunteer-association/index.html.twig', [
            'controller_name' => 'VolunteerAssociationController',
            'current_locale' => $_locale,
            'site' => 'volunteer-association',
        ]);
    }
}
