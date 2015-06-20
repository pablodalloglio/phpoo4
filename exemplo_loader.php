<?php
// Lib loader
require_once 'Lib/Livro/Core/ClassLoader.php';
$al= new Livro\Core\ClassLoader;
$al->addNamespace('Livro', 'Lib/Livro');
$al->register();

use Livro\Database\Connection;
$obj1 = Connection::open('livro');
var_dump($obj1);