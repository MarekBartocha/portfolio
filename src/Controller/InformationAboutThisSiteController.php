<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InformationAboutThisSiteController extends AbstractController
{
    #[Route('/{_locale}/information-about-this-site', name: 'information-about-this-site_index')]
    public function index(string $_locale): Response
    {
        return $this->render('information-about-this-site/index.html.twig', [
            'controller_name' => 'InformationAboutThisSiteController',
            'current_locale' => $_locale,
            'site' => 'information-about-this-site',
        ]);
    }
}
