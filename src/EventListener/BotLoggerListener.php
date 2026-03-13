<?php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Security;

class BotLoggerListener
{
    private bool $logged = false;
    private RouterInterface $router;
    private Security $security;

    public function __construct(RouterInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $ip = $request->getClientIp();
        $path = $request->getPathInfo();

        // Anonimizujemy IP: ostatni oktet zawsze 0
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ipParts = explode('.', $ip);
            if (count($ipParts) === 4) {
                $ipParts[3] = '0';
                $ip = implode('.', $ipParts);
            }
        }

        $locale = 'pl';
        if (preg_match('#^/(en|pl|de)(/|$)#', $path, $m)) {
            $locale = $m[1];
        }

        // Ignorujemy narzędzia debugowe i favicon
        if (preg_match('#^/(_wdt|_profiler|favicon\.ico)#', $path)) {
            return;
        }

        if ($path === '/' || $path === '/'.$locale.'/') {
            return;
        }

        $routes = $this->router->getRouteCollection()->all();
        $validPaths = [];
        foreach ($routes as $name => $route) {
            $routePath = $route->getPath();
            if (str_starts_with($routePath, '/_')) {
                continue;
            }
            $validPaths[] = str_replace('{_locale}', $locale, $routePath);
        }

        $user = $this->security->getUser();

        $logDir = __DIR__.'/../../var/log';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $knownBotIpsFile = $logDir.'/bot_ips.txt';
        if (!file_exists($knownBotIpsFile)) {
            touch($knownBotIpsFile);
            chmod($knownBotIpsFile, 0666);
        }
        $knownBotIps = array_filter(array_map('trim', file($knownBotIpsFile)));

        $knownAdminIpsFile = $logDir.'/admin_ips.txt';
        if (!file_exists($knownAdminIpsFile)) {
            touch($knownAdminIpsFile);
            chmod($knownAdminIpsFile, 0666);
        }
        $knownAdminIps = array_filter(array_map('trim', file($knownAdminIpsFile)));

        if (in_array($ip, $knownAdminIps, true)) {
            $type = 'ADMIN';
        } elseif ($user !== null && $this->security->isGranted('ROLE_ADMIN')) {
            $type = 'ADMIN';
        } elseif ($user !== null) {
            $type = 'HUMAN';
        } elseif (in_array($ip, $knownBotIps, true)) {
            $type = 'BOT';
        } else {
            $matchedPath = false;
            foreach ($validPaths as $validPath) {
                $regex = preg_replace('/\{[^\}]+\}/', '[^/]+', $validPath);
                if (preg_match('#^'.$regex.'$#', $path)) {
                    $matchedPath = true;
                    break;
                }
            }
            $type = $matchedPath ? 'HUMAN' : 'BOT';
        }

        if ($type === 'ADMIN' && !in_array($ip, $knownAdminIps, true)) {
            file_put_contents($knownAdminIpsFile, $ip.PHP_EOL, FILE_APPEND | LOCK_EX);
            $knownAdminIps[] = $ip;
        }
        if ($type === 'BOT' && !in_array($ip, $knownBotIps, true)) {
            file_put_contents($knownBotIpsFile, $ip.PHP_EOL, FILE_APPEND | LOCK_EX);
            $knownBotIps[] = $ip;
        }

        $data = [date('Y-m-d H:i:s'), $ip, $type, $path];
        file_put_contents(
            __DIR__.'/../../var/log/ip.log',
            implode(' ', $data) . PHP_EOL,
            FILE_APPEND
        );
    }
}
