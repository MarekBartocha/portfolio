<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PrivacyPolicyController extends AbstractController
{
    #[Route('{_locale}/privacy-policy', name: 'privacy_policy_index')]
    public function index(string $_locale): Response
    {
        return $this->render('privacy_policy/index.html.twig', [
            'controller_name' => 'PrivacyPolicyController',
            'current_locale' => $_locale,
            'site' => 'privacy-policy',
        ]);
    }
}
