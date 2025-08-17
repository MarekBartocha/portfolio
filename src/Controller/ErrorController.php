<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ErrorController
{
    public function __invoke(\Throwable $exception): RedirectResponse
    {
        if ($exception instanceof NotFoundHttpException) {
            return new RedirectResponse('/');
        }

        // inne błędy – np. 500 – zostaw
        throw $exception;
    }
}
