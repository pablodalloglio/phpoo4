<?php
Namespace Livro\Control;

/**
 * classe TAction
 * encapsula uma ação
 */
class Action
{
    private $action;
    private $param;
    
    /**
     * método __construct()
     * instancia uma nova ação
     * @param $action = método a ser executado
     */
    public function __construct(Callable $action)
    {
        $this->action = $action;
    }
    
    /**
     * método setParameter()
     * acrescenta um parâmetro ao método a ser executdao
     * @param $param = nome do parâmetro
     * @param $value = valor do parâmetro
     */
    public function setParameter($param, $value)
    {
        $this->param[$param] = $value;
    }
    
    /**
     * método serialize()
     * transforma a ação em uma string do tipo URL
     */
    public function serialize()
    {
        // verifica se a ação é um método
        if (is_array($this->action))
        {
            // obtém o nome da classe
            $url['class'] = get_class($this->action[0]);
            // obtém o nome do método
            $url['method'] = $this->action[1];
        }
        else if (is_string($this->action)) // é uma string
        {
            // obtém o nome da função
            $url['method'] = $this->action;
        }
        // verifica se há parâmetros
        if ($this->param)
        {
            $url = array_merge($url, $this->param);
        }
        // monta a URL
        return '?' . http_build_query($url);
    }
}
