<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BotIp;

class StatsController extends AbstractController
{
    private RouterInterface $router;
    private EntityManagerInterface $em;

    public function __construct(RouterInterface $router, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    #[Route('/{_locale}/admin/stats', name: 'stats')]
    public function index(string $_locale): Response
    {
        // Pobierz wszystkie bot IP do tablicy dla szybkiego sprawdzania
        $botIps = $this->em->getRepository(BotIp::class)->createQueryBuilder('b')
            ->select('b.ip')
            ->getQuery()
            ->getArrayResult();

        // Zamień na prostą tablicę stringów IP
        $botIpsList = array_map(fn($item) => $item['ip'], $botIps);

        $logFile = __DIR__ . '/../../var/log/visit_log.log';
        $visitsPerDay = [];
        $uniqueIpsPerDay = [];
        $adminVisitsPerDay = [];
        $rawLogLines = [];

        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $last30Days = new \DateTime('-30 days');

            foreach ($lines as $line) {
                $parts = explode('|', $line, 4);
                if (count($parts) < 4) {
                    continue; // pomijamy niepoprawne wpisy
                }

                [$datetime, $ip, $type, $path] = $parts;

                // Pomijamy wewnętrzne żądania Symfony i favicon
                if (preg_match('#^/_wdt|^/_profiler|^/favicon\.ico#', $path)) {
                    continue;
                }

                $date = substr($datetime, 0, 10);
                $logDate = new \DateTime($date);

                // Pomijamy starsze niż 30 dni
                if ($logDate < $last30Days) {
                    continue;
                }

                // Jeśli IP jest na liście botów, ustaw typ jako BOT
                if (in_array($ip, $botIpsList, true)) {
                    $type = 'BOT';
                }

                // Inicjalizacja
                if (!isset($visitsPerDay[$date])) {
                    $visitsPerDay[$date] = ['BOT' => 0, 'HUMAN' => 0];
                    $uniqueIpsPerDay[$date] = [];
                    $adminVisitsPerDay[$date] = 0;
                }

                $visitsPerDay[$date][$type]++;

                // Zlicz unikalne IP tylko dla ludzi
                if ($type === 'HUMAN') {
                    $uniqueIpsPerDay[$date][$ip] = true;
                }

                // Zlicz wejścia na admin
                if (preg_match('#^/(pl|en)?/admin#', $path)) {
                    $adminVisitsPerDay[$date]++;
                }

                // Dodajemy wszystkie wpisy do listy szczegółowej
                $rawLogLines[] = [
                    'datetime' => $datetime,
                    'ip'       => $ip,
                    'type'     => $type,
                    'path'     => $path,
                ];
            }
        }

        ksort($visitsPerDay);
        ksort($uniqueIpsPerDay);
        ksort($adminVisitsPerDay);

        $uniqueVisits = [];
        foreach ($uniqueIpsPerDay as $date => $ips) {
            $uniqueVisits[$date] = count($ips);
        }

        return $this->render('stats/index.html.twig', [
            'dates'          => array_keys($visitsPerDay),
            'humans'         => array_column($visitsPerDay, 'HUMAN'),
            'bots'           => array_column($visitsPerDay, 'BOT'),
            'unique_visits'  => array_values($uniqueVisits),
            'admin_visits'   => array_values($adminVisitsPerDay),
            'raw_logs'       => $rawLogLines,
            'current_locale' => $_locale,
            'site'           => 'admin/stats',
        ]);
    }
}
