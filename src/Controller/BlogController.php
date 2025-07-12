<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BlogController extends AbstractController
{
    #[Route('/{_locale}/blog', name: 'blog_index')]
    public function index(string $_locale): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'current_locale' => $_locale,
            'site' => 'blog',
        ]);
    }
}
