<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SliderController extends AbstractController
{
    #[Route('/{_locale}/slider/{folder}/{number}/{id}', name: 'slider_index')]
    public function index(string $_locale, string $folder, int $number, int $id): Response
    {
        
        return $this->render('slider/index.html.twig', [
            'controller_name' => 'SliderController',
            'current_locale' => $_locale,
            'site' => 'slider',
            'folder' => $folder,
            'number' => $number,
            'id' => $id,
        ]);
    }
}
