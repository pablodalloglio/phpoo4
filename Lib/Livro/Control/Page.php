<?php
Namespace Livro\Control;

use Livro\Widgets\Base\Element;

/**
 * classe Page
 * classe para controle do fluxo de execução
 */
class Page extends Element
{
    /**
     * método __construct()
     */
    public function __construct()
    {
        // define o elemento que irá representar
        parent::__construct('div');
    }
    
    /**
     * método show()
     * exibe o conteúdo da página
     */
    public function show()
    {
        $this->run();
        parent::show();
    }
    
    /**
     * método run()
     * executa determinado método de acordo com os parâmetros recebidos
     */
    public function run()
    {
        if ($_GET)
        {
            $class = isset($_GET['class']) ? $_GET['class'] : NULL;
            $method = isset($_GET['method']) ? $_GET['method'] : NULL;
            if ($class)
            {
                $object = $class == get_class($this) ? $this : new $class;
                if (method_exists($object, $method))
                {
                    call_user_func(array($object, $method), $_GET);
                }
            }
            else if (function_exists($method))
            {
                call_user_func($method, $_GET);
            }
        }
    }
}
