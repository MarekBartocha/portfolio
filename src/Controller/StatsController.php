<?php
// src/Controller/StatsController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatsController extends AbstractController
{
    #[Route('/{_locale}/admin/stats', name: 'stats')]
    public function index(string $_locale): Response
    {
        $logFile = __DIR__ . '/../../var/log/visit_log.log';
        $visitsPerDay = [];
        $uniqueIpsPerDay = [];
        $adminVisitsPerDay = [];
        $rawLogLines = [];

        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                [$datetime, $ip, $type, $path] = explode('|', $line, 5);

                // Pomijamy wewnętrzne żądania Symfony i favicon
                if (preg_match('#^/_wdt|^/_profiler|^/favicon\.ico#', $path)) {
                    continue;
                }

                $rawLogLines[] = [
                    'datetime' => $datetime,
                    'ip' => $ip,
                    'type' => $type,
                    'path' => $path,
                ];

                $date = substr($datetime, 0, 10); // yyyy-mm-dd

                if (!isset($visitsPerDay[$date])) {
                    $visitsPerDay[$date] = ['BOT' => 0, 'HUMAN' => 0];
                    $uniqueIpsPerDay[$date] = [];
                    $adminVisitsPerDay[$date] = 0;
                }

                $visitsPerDay[$date][$type]++;

                // Zlicz unikalne IP tylko dla ludzi
                if ($type === 'HUMAN') {
                    $uniqueIpsPerDay[$date][$ip] = true; // hash IP per dzień
                }

                // Zliczaj wejścia na strony admina
                if (preg_match('#^/(pl|en)?/admin#', $path)) {
                    $adminVisitsPerDay[$date]++;
                }
            }

        }

        // Sortowanie po dacie
        ksort($visitsPerDay);
        ksort($uniqueIpsPerDay);
        ksort($adminVisitsPerDay);

        // Liczba unikalnych IP (wizyt) per dzień
        $uniqueVisits = [];
        foreach ($uniqueIpsPerDay as $date => $ips) {
            $uniqueVisits[$date] = count($ips);
        }

        return $this->render('stats/index.html.twig', [
            'dates' => array_keys($visitsPerDay),
            'humans' => array_column($visitsPerDay, 'HUMAN'),
            'bots' => array_column($visitsPerDay, 'BOT'),
            'unique_visits' => array_values($uniqueVisits),
            'admin_visits' => array_values($adminVisitsPerDay),
            'raw_logs' => $rawLogLines,
            'current_locale' => $_locale,
            'site' => 'admin/stats',
        ]);
    }
}
