<?php
/**
 * classe Session
 * gerencia uma seção com o usuário
 */
class Session
{
    /**
     * método construtor
     * inicializa uma seção
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * método setValue()
     * armazena uma variável na seção
     * @param $var     = Nome da variável
     * @param $value = Valor
     */
    public static function setValue($var, $value)
    {
        $_SESSION[$var] = $value;
    }

    /**
     * método getValue()
     * retorna uma variável da seção
     * @param $var = Nome da variável
     */
    public static function getValue($var)
    {
        if (isset($_SESSION[$var]))
        {
            return $_SESSION[$var];
        }
    }

    /**
     * método freeSession()
     * destrói os dados de uma seção
     */
    public static function freeSession()
    {
        $_SESSION = array();
        session_destroy();
    }
}
