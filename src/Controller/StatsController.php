<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatsController extends AbstractController
{
    #[Route('/{_locale}/admin/stats', name: 'stats')]
    public function index(string $_locale): Response
    {
        $logFile = __DIR__ . '/../../var/log/ip.log';
        $visitsPerDay = [];
        $uniqueIpsPerDay = [];
        $rawLogLines = [];
        $knownBots = [];
        $knownAdmins = [];

        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $last30Days = new \DateTime('-30 days');

            // 1️⃣ Zbieramy wszystkie IP, które kiedykolwiek były BOT lub ADMIN, aby później poprawnie klasyfikować logi
            foreach ($lines as $line) {
                $parts = preg_split('/\s+/', $line, 5);
                if (count($parts) < 4) continue;

                [$date, $time, $logIp, $logType] = array_slice($parts, 0, 4);

                if ($logType === 'BOT') {
                    $knownBots[$logIp] = true;
                }

                if ($logType === 'ADMIN') {
                    $knownAdmins[$logIp] = true;
                }
            }

            // 2️⃣ Przetwarzamy log
            foreach ($lines as $line) {
                $parts = preg_split('/\s+/', $line, 5);
                if (count($parts) < 4) continue;

                $datetime = $parts[0] . ' ' . $parts[1];
                $ip = $parts[2];
                $type = $parts[3];
                $path = $parts[4] ?? '';

                // Pomijamy nieistotne ścieżki
                if (preg_match('#^/_wdt|^/_profiler|^/favicon\.ico#', $path)) continue;

                $date = substr($datetime, 0, 10);
                $logDate = new \DateTime($date);
                if ($logDate < $last30Days) continue;
                
                // Typ BOT jeśli kiedykolwiek było w logu jako bot
                if (isset($knownBots[$ip])) {
                    $type = 'BOT';
                }

                // ADMIN jeśli kiedykolwiek był adminem
                if (isset($knownAdmins[$ip])) {
                    $type = 'ADMIN';
                }

                // Inicjalizacja tablic
                if (!isset($visitsPerDay[$date])) {
                    $visitsPerDay[$date] = ['BOT' => 0, 'HUMAN' => 0, 'ADMIN' => 0];
                    $uniqueIpsPerDay[$date] = [];
                    $adminVisitsPerDay[$date] = 0;
                }

                // Typ do wykresu
                if ($type === 'BOT') {
                    $visitsPerDay[$date]['BOT']++;
                } elseif ($type === 'ADMIN') {
                    $visitsPerDay[$date]['ADMIN']++;
                    $adminVisitsPerDay[$date]++;
                } else {
                    $visitsPerDay[$date]['HUMAN']++;
                }

                // Liczymy unikalne IP tylko dla HUMAN
                if ($type === 'HUMAN') {
                    $uniqueIpsPerDay[$date][$ip] = true;
                }

                // Tabela logów – tylko HUMAN
                if ($type === 'HUMAN') {
                    $rawLogLines[] = [
                        'datetime' => $datetime,
                        'ip'       => $ip,
                        'type'     => $type,
                        'path'     => $path,
                    ];
                }
            }
        }

        ksort($visitsPerDay);
        ksort($uniqueIpsPerDay);

        $uniqueVisits = [];
        foreach ($uniqueIpsPerDay as $date => $ips) {
            $uniqueVisits[$date] = count($ips);
        }

        $rawLogLines = array_reverse($rawLogLines);

        return $this->render('stats/index.html.twig', [
            'dates'          => array_keys($visitsPerDay),
            'humans'         => array_column($visitsPerDay, 'HUMAN'),
            'admins'         => array_column($visitsPerDay, 'ADMIN'),
            'bots'           => array_column($visitsPerDay, 'BOT'),
            'unique_visits'  => array_values($uniqueVisits),
            'raw_logs'       => $rawLogLines,
            'current_locale' => $_locale,
            'site'           => 'admin/stats',
        ]);
    }
}
