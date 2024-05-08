<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class ResponseHeaderListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $response->headers->set('x-task', '1');
    }
}
