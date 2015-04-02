<?php
/**
 * classe Session
 * gerencia uma seзгo com o usuбrio
 */
class Session
{
    /**
     * mйtodo construtor
     * inicializa uma seзгo
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * mйtodo setValue()
     * armazena uma variбvel na seзгo
     * @param $var     = Nome da variбvel
     * @param $value = Valor
     */
    public static function setValue($var, $value)
    {
        $_SESSION[$var] = $value;
    }

    /**
     * mйtodo getValue()
     * retorna uma variбvel da seзгo
     * @param $var = Nome da variбvel
     */
    public static function getValue($var)
    {
        if (isset($_SESSION[$var]))
        {
            return $_SESSION[$var];
        }
    }

    /**
     * mйtodo freeSession()
     * destrуi os dados de uma seзгo
     */
    public static function freeSession()
    {
        $_SESSION = array();
        session_destroy();
    }
}
