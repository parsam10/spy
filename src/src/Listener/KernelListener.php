<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelListener
{

    public function onKernelRequest(RequestEvent $event)
    {

        $request = $event->getRequest();
        $request->setLocale("fa");
    }

}