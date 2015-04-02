<?php
require_once 'Lib/Livro/Core/ClassLoader.php';
$al= new Livro\Core\ClassLoader;
$al->setIncludePath('Lib');
$al->register();

class LivroSoapServer
{
    public function __call($method, $parameters)
    {
        $class    = isset($_REQUEST['class']) ? $_REQUEST['class']   : '';
        $response = NULL;
        
        // aqui implementar mecanismo de controle !!
        if (!in_array($class, array('CustomerService')))
        {
            throw new SoapFault('server', _t('Permission denied'));
        }
        
        try
        {
            if (class_exists($class))
            {
                if (method_exists($class, $method))
                {
                    return call_user_func_array(array(new $class($_GET), $method),$parameters);
                }
                else
                {
                    throw new SoapFault('server', "Method $class::$method not found");
                }
            }
            else
            {
                throw new SoapFault('server', "Class $class not found");
            }
        }
        catch (Exception $e)
        {
            throw new SoapFault('server', $e->getMessage());
        }
    }
}

$server = new SoapServer(NULL, array('encoding' => 'UTF-8', 'uri' => 'http://test-uri/'));
$server->setClass('LivroSoapServer');
$server->handle();
