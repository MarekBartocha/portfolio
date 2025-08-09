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

        // Ignoruj ścieżki do narzędzi debugowych itp.
        if (preg_match('#^/(_(wdt|profiler)|favicon\.ico)#', $path)) {
            return;
        }

        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        $userAgent = $request->headers->get('User-Agent', '');

        $type = $this->isBot($userAgent) ? 'BOT' : 'HUMAN';

        $logEntry = "$datetime|$ip|$type|$path\n";

        file_put_contents(__DIR__ . '/../../var/log/visit_log.log', $logEntry, FILE_APPEND);
    }

    private function isBot(string $userAgent): bool
    {
        $userAgent = strtolower($userAgent);

        $botKeywords = [
            'bot', 'crawl', 'slurp', 'spider', 'fetch', 'monitor', 'pingdom', 'facebookexternalhit',
            'headless', 'phantomjs', 'python', 'curl', 'wget', 'java', 'libwww-perl',
            'scrapy', 'axios', 'go-http-client', 'httpclient', 'mj12bot', 'ahrefsbot', 'semrushbot',
            'bingpreview', 'slackbot', 'discordbot', 'embedly', 'quora link preview', 'whatsapp'
        ];

        foreach ($botKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
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
            return preg_replace('/\.\d+$/', '.0', $ip);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return preg_replace('/(:[a-f0-9]+){1,4}$/i', '::', $ip);
        }

        return $ip;
    }
}


