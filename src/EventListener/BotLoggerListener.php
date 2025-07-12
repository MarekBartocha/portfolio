<?php

// src/EventListener/BotLoggerListener.php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class BotLoggerListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $ip = $this->anonymizeIp($request->getClientIp());
        $path = $request->getPathInfo();

        // Ignorujemy nieistotne ścieżki (favicon, debugbar itd.)
        if (preg_match('#^/(_(wdt|profiler)|favicon\.ico)#', $path)) {
            return;
        }

        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        $type = $this->isBot($request->headers->get('User-Agent', '')) ? 'BOT' : 'HUMAN';

        $logEntry = "$datetime|$ip|$type|$path\n";

        file_put_contents(__DIR__ . '/../../var/log/visit_log.log', $logEntry, FILE_APPEND);
    }

    private function isBot(string $userAgent): bool
    {
        $bots = [
            'Googlebot', 'Bingbot', 'Slurp', 'DuckDuckBot', 'Baiduspider',
            'YandexBot', 'Sogou', 'Exabot', 'facebot', 'ia_archiver', 'MJ12bot', 'AhrefsBot'
        ];

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }
        return false;
    }

    private function anonymizeIp(?string $ip): string
    {
        if (!$ip) {
            return '0.0.0.0';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // np. 192.168.1.123 → 192.168.1.0
            return preg_replace('/\.\d+$/', '.0', $ip);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // np. skrócenie IPv6 do prefiksu /64
            return preg_replace('/(:[a-f0-9]+){1,4}$/i', '::', $ip);
        }

        return $ip;
    }
}


