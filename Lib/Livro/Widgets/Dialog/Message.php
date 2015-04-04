<?php
Namespace Livro\Widgets\Dialog;

use Livro\Widgets\Base\Element;

/**
 * classe TMessage
 * exibe mensagens ao usuário
 */
class Message
{
    /**
     * método construtor
     * instancia objeto TMessage
     * @param $type      = tipo de mensagem (info, error)
     * @param $message = mensagem ao usuário
     */
    public function __construct($type, $message)
    {
        echo $message;
    }
}
