<?php
namespace Livro\Control;

/**
 * Encapsula uma ação
 * @author Pablo Dall'Oglio
 */
class Action implements ActionInterface
{
    private $action;
    private $param;
    
    /**
     * Instancia uma nova ação
     * @param $action = método a ser executado
     */
    public function __construct(Callable $action)
    {
        $this->action = $action;
    }
    
    /**
     * Acrescenta um parâmetro ao método a ser executdao
     * @param $param = nome do parâmetro
     * @param $value = valor do parâmetro
     */
    public function setParameter($param, $value)
    {
        $this->param[$param] = $value;
    }
    
    /**
     * Transforma a ação em uma string do tipo URL
     */
    public function serialize()
    {
        // verifica se a ação é um método
        if (is_array($this->action))
        {
            // obtém o nome da classe
            $url['class'] = is_object($this->action[0]) ? get_class($this->action[0]) : $this->action[0];
            // obtém o nome do método
            $url['method'] = $this->action[1];
            
            // verifica se há parâmetros
            if ($this->param)
            {
                $url = array_merge($url, $this->param);
            }
            // monta a URL
            return '?' . http_build_query($url);
        }
    }
}
