<?php
try
{
    $arguments = array();
    $arguments['encoding']   = 'UTF-8';
    $arguments['exceptions'] = true;
    $arguments['location']   = 'http://git/phpoo/soap.php?class=PessoaServices';
    $arguments['uri']        = "http://test-uri/";
    $arguments['trace']      = 1;
    
    // cria o client
    $client = new SoapClient(NULL, $arguments);
    print_r( $client->getData(19) );
}
catch (Exception $e)
{
    echo $e->getMessage();
}
