<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class RequestListener
{

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (HttpKernel::MASTER_REQUEST === $event->getRequestType() && $request->getMethod() === 'GET') {
            if ($request->query->get('_method') === 'PUT') {
                $request->setMethod('PUT');
            } elseif ($request->query->get('_method') === 'DELETE') {
                $request->setMethod('DELETE');
            }
        }
    }

}
