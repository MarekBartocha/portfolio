<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/{_locale}', name: 'home_index')]
    public function index(TranslatorInterface $translator, Request $request, string $_locale): Response
    {
        $request->getSession()->set('_locale', $_locale);
        $request->setLocale($_locale);

        return $this->render('home/index.html.twig', [
            'current_locale' => $_locale,
            'site' => '',
        ]);
    }
}
