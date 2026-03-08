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
        $logFile = __DIR__ . '/../../var/log/visit_log.log';
        $visitsPerDay = [];
        $uniqueIpsPerDay = [];
        $rawLogLines = [];
        $knownBots = [];

        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $last30Days = new \DateTime('-30 days');

            // 1️⃣ Zbieramy wszystkie IP, które kiedykolwiek były BOT
            foreach ($lines as $line) {
                $parts = explode('|', $line, 4);
                if (count($parts) < 4) continue;
                [, $logIp, $logType, ] = $parts;
                if ($logType === 'BOT') {
                    $knownBots[$logIp] = true;
                }
            }

            // 2️⃣ Przetwarzamy log
            foreach ($lines as $line) {
                $parts = explode('|', $line, 4);
                if (count($parts) < 4) continue;

                [$datetime, $ip, $type, $path] = $parts;

                // Pomijamy nieistotne ścieżki debug
                if (preg_match('#^/_wdt|^/_profiler|^/favicon\.ico#', $path)) {
                    continue;
                }

                $date = substr($datetime, 0, 10);
                $logDate = new \DateTime($date);

                if ($logDate < $last30Days) continue;

                // Sprawdzenie czy zalogowany użytkownik jest adminem
                $isAdmin = $this->getUser() && in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true);

                // Nadpisanie typu
                if ($isAdmin) {
                    $type = 'ADMIN';
                } elseif (isset($knownBots[$ip])) {
                    $type = 'BOT';
                } else {
                    $type = 'HUMAN';
                }

                // Inicjalizacja tablic
                if (!isset($visitsPerDay[$date])) {
                    $visitsPerDay[$date] = ['BOT' => 0, 'HUMAN' => 0, 'ADMIN' => 0];
                    $uniqueIpsPerDay[$date] = [];
                }

                // Zliczamy odwiedziny
                $visitsPerDay[$date][$type]++;

                // Zliczamy unikalnych HUMAN i ADMIN
                if ($type === 'HUMAN' || $type === 'ADMIN') {
                    $uniqueIpsPerDay[$date][$ip] = true;

                    // Dodajemy do tabeli logów
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
