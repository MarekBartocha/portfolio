<?php
// src/Controller/AdminBotController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BotIp;

class AdminBotController extends AbstractController
{
    #[Route('/admin/mark-bot/{ip}', name: 'admin_mark_bot')]
    public function markBot(string $ip, EntityManagerInterface $em): RedirectResponse
    {
        // Tu dodaj logikę sprawdzenia IP w bazie i ewentualnego dodania

        $botIp = $em->getRepository(BotIp::class)->findOneBy(['ip' => $ip]);
        if (!$botIp) {
            $botIp = new BotIp();
            $botIp->setIp($ip);
            $em->persist($botIp);
            $em->flush();
            $this->addFlash('success', "IP $ip dodane jako bot.");
        } else {
            $this->addFlash('info', "IP $ip już jest w bazie botów.");
        }

        return $this->redirectToRoute('stats', ['_locale' => $this->getParameter('locale')]);
    }
}
