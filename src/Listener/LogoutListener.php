<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $event): void
    {
        $response = $event->getResponse();
        $response->headers->clearCookie('BEARER');
        $response->headers->clearCookie('REFRESH_TOKEN');

        $event->setResponse($response);
    }
}
