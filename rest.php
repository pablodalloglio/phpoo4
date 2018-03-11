<?php
header('Content-Type: application/json; charset=utf-8');

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

class LivroRestServer
{
    public static function run($request)
    {
        $class    = isset($request['class'])  ? $request['class']  : '';
        $method   = isset($request['method']) ? $request['method'] : '';
        $response = NULL;
        
        try
        {
            if (class_exists($class))
            {
                if (method_exists($class, $method))
                {
                    $response = call_user_func(array($class, $method), $request);
                    return json_encode( array('status' => 'success', 'data' => $response));
                }
                else
                {
                    $error_message = "Método {$class}::{$method} não encontrado";
                    return json_encode( array('status' => 'error', 'data' => $error_message));
                }
            }
            else
            {
                $error_message = "Classe {$class} não encontrada";
                return json_encode( array('status' => 'error', 'data' => $error_message));
            }
        }
        catch (Exception $e)
        {
            return json_encode( array('status' => 'error', 'data' => $e->getMessage()));
        }
    }
}

print LivroRestServer::run($_REQUEST);
