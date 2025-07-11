<?php
// src/Controller/StatsController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatsController extends AbstractController
{
    #[Route('/{_locale}/stats', name: 'stats')]
    public function index(string $_locale): Response
    {
        $logFile = __DIR__ . '/../../var/visit_log.log';
        $visitsPerDay = [];

        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                [$datetime, $ip, $type, $path, $userAgent] = explode('|', $line, 5);

                // Pomijamy wewnętrzne żądania Symfony i favicon
                if (preg_match('#^/_wdt|^/_profiler|^/favicon\.ico#', $path)) {
                    continue;
                }

                $date = substr($datetime, 0, 10); // yyyy-mm-dd

                if (!isset($visitsPerDay[$date])) {
                    $visitsPerDay[$date] = ['BOT' => 0, 'HUMAN' => 0];
                }

                $visitsPerDay[$date][$type]++;
            }

        }

        // Sortowanie po dacie
        ksort($visitsPerDay);

        return $this->render('stats/index.html.twig', [
            'dates' => array_keys($visitsPerDay),
            'humans' => array_column($visitsPerDay, 'HUMAN'),
            'bots' => array_column($visitsPerDay, 'BOT'),
            'current_locale' => $_locale,
            'site' => 'stats',
        ]);
    }
}
