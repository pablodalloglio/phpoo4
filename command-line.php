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

use Livro\Database\Transaction;

try
{
    Transaction::open('livro');
    var_dump( Pessoa::find(1)->toArray() );
    Transaction::close();
}
catch (Exception $e)
{
    echo $e->getMessage();
}