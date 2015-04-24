<?php
namespace Livro\Control;

use Livro\Widgets\Base\Element;

/**
 * Encapsula uma página
 * @author Pablo Dall'Oglio
 */
class Page extends Element
{
    /**
     * Define o elemento wrapper
     */
    public function __construct()
    {
        parent::__construct('div');
    }
    
    /**
     * Exibe o conteúdo da página
     */
    public function show()
    {
        $this->run();
        parent::show();
    }
    
    /**
     * Executa determinado método de acordo com os parâmetros recebidos
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
        }
    }
}
