<?php

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
        $userAgent = $request->headers->get('User-Agent', '');
        $ip = $request->getClientIp();
        $path = $request->getPathInfo();
        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        $isBot = $this->isBot($userAgent) ? 'BOT' : 'HUMAN';

        $logEntry = "$datetime|$ip|$isBot|$path|$userAgent\n";

        file_put_contents(__DIR__ . '/../../var/visit_log.log', $logEntry, FILE_APPEND);
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
}


