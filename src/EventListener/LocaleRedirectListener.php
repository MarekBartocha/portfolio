<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class LocaleRedirectListener
{
    private RouterInterface $router;
    
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // Sprawdzenie czy użytkownik jest na stronie głównej
        if ($path === '/' || $path === '/') {
            $locale = $request->getPreferredLanguage(['pl', 'en']) ?? 'en';

            // Przekierowanie na odpowiednią wersję językową
            $response = new RedirectResponse("$locale/");
            $event->setResponse($response);
        }
    }
}
