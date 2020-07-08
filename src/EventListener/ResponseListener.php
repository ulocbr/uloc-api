<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

/*
 * Escuta criada para permitir XMLHttpRequest para o API
 * Tiago Felipe <tiago@tiagofelipe.com>
 */

namespace Uloc\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseListener
{

    public function onKernelResponse(ResponseEvent $event)
    { // TODO: Changed from FilterResponseEvent
        $response = $event->getResponse();
        $request = $event->getRequest();
        $responseHeaders = $response->headers;

        $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept, authorization, cache-control');
        if (empty($responseHeaders->get('Access-Control-Allow-Origin'))) {
            $responseHeaders->set('Access-Control-Allow-Origin', '*');
        }
        $responseHeaders->set('Access-Control-Max-Age', '1000');
        $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');

        if ($request->getMethod() == 'OPTIONS') {
            $response->setStatusCode(200);
            $response->setContent(null);
            return;
        }

        return $response;
    }

}
