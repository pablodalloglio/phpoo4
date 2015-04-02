<?php
require_once 'Lib/Livro/Core/ClassLoader.php';
$al= new Livro\Core\ClassLoader;
$al->setIncludePath('Lib');
$al->register();

$template = file_get_contents('App/Templates/template.html');
$content = '';
if ($_GET)
{
    $class = $_GET['class'];
    if (class_exists($class))
    {
        $pagina = new $class;
        ob_start();
        $pagina->show();
        $content = ob_get_contents();
        ob_end_clean();
    }
    else if (function_exists($method))
    {
        call_user_func($method, $_GET);
    }
}
echo str_replace('#CONTENT#', $content, $template);
