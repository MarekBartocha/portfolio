<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuizController extends AbstractController
{
    #[Route('/{_locale}/quiz', name: 'quiz_index')]
    public function index(string $_locale): Response
    {
        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController',
            'current_locale' => $_locale,
            'site' => 'quiz',
        ]);
    }
}
