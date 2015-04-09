<?php
date_default_timezone_set('America/Sao_Paulo');

require_once 'Lib/Livro/Core/ClassLoader.php';
$al= new Livro\Core\ClassLoader;
$al->addNamespace('Livro', 'Lib/Livro');
$al->register();

require_once 'Lib/Livro/Core/AppLoader.php';
$al= new Livro\Core\AppLoader;
$al->addDirectory('App/Control');
$al->addDirectory('App/Model');
$al->register();

$template = file_get_contents('App/Templates/template.html');
$content = '';
if ($_GET)
{
    $class = $_GET['class'];
    if (class_exists($class))
    {
        try
        {
            $pagina = new $class;
            ob_start();
            $pagina->show();
            $content = ob_get_contents();
            ob_end_clean();
        }
        catch (Exception $e)
        {
            $content = $e->getMessage() . '<br>' .$e->getTraceAsString();
        }
    }
}
echo str_replace('{content}', $content, $template);
