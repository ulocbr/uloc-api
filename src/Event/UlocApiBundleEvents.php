<?php


namespace Uloc\ApiBundle\Event;


final class UlocApiBundleEvents
{
    /**
     * Chamado diretamente quando um novo token de usuário da API é requisitado
     *
     * @Event("Uloc\Bundle\AppBundle\Event\PagamentoFaturaEvent")
     */
    const EVENT_USER_NEW_TOKEN = 'uloc_api.user.new_token';

}