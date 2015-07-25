<?php
// Lib loader
require_once 'Lib/Livro/Core/ClassLoader.php';
$al= new Livro\Core\ClassLoader;
$al->addNamespace('Livro', 'Lib/Livro');
$al->register();

// App loader
require_once 'Lib/Livro/Core/AppLoader.php';
$al= new Livro\Core\AppLoader;
$al->addDirectory('App/Control');
$al->addDirectory('App/Model');
$al->addDirectory('App/Services');
$al->register();

class LivroSoapServer
{
    public function __call($method, $parameters)
    {
        $class = isset($_REQUEST['class']) ? $_REQUEST['class']   : '';
        
        // aqui implementar mecanismo de controle !!
        if (!in_array($class, array('PessoaServices'))) {
            throw new SoapFault('server', 'Permission denied');
        }
        
        try {
            if (class_exists($class)) {
                if (method_exists($class, $method)) {
                    return call_user_func_array(array(new $class($_GET), $method),$parameters);
                }
                else {
                    throw new SoapFault('server', "Método $class::$method não encontrado");
                }
            }
            else {
                throw new SoapFault('server', "Classe $class não encontrada");
            }
        }
        catch (Exception $e) {
            throw new SoapFault('server', $e->getMessage());
        }
    }
}

$server = new SoapServer(NULL, array('encoding' => 'UTF-8', 'uri' => 'http://test-uri/'));
$server->setClass('LivroSoapServer');
$server->handle();
