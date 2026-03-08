<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class BotLoggerListener
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // 1️⃣ Ignorujemy narzędzia debugowe i favicon
        if (preg_match('#^/(_wdt|_profiler|favicon\.ico)#', $path)) {
            return;
        }

        $ip = $this->anonymizeIp($request->getClientIp());
        $datetime = (new \DateTime())->format('Y-m-d H:i:s');

        // 2️⃣ Sprawdzamy czy istnieje route – jeśli nie, oznaczamy BOT
        $type = $request->attributes->get('_route') ? 'HUMAN' : 'BOT';

        // 3️⃣ Tworzymy wpis logu
        $logEntry = "$datetime|$ip|$type|$path\n";

        // 4️⃣ Zapis do pliku z LOCK_EX
        file_put_contents(
            __DIR__ . '/../../var/log/visit_log.log',
            $logEntry,
            FILE_APPEND | LOCK_EX
        );
    }

    private function anonymizeIp(?string $ip): string
    {
        if (!$ip) {
            return '0.0.0.0';
        }

        // IPv4 → ostatnia liczba 0
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\.\d+$/', '.0', $ip);
        }

        // IPv6 → ostatnia część ::
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return preg_replace('/(:[a-f0-9]+){1,4}$/i', '::', $ip);
        }

        return $ip;
    }
}
