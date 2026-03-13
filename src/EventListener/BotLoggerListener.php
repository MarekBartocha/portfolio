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
        $roles = [];
        if ($user !== null && method_exists($user, 'getRoles')) {
            $roles = $user->getRoles();
        }

        if ($user !== null && $this->security->isGranted('ROLE_ADMIN')) {
            $type = 'ADMIN';
        } elseif ($user !== null) {
            $type = 'HUMAN';
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

        $data = [date('Y-m-d H:i:s'), $ip, $type, $path];
        file_put_contents(
            __DIR__.'/../../var/log/ip.log',
            implode(' ', $data) . PHP_EOL,
            FILE_APPEND
        );
    }
}
