<?php
Namespace Livro\Database;

/*
 * classe Logger
 * Esta classe provê uma interface abstrata para definição de algoritmos de LOG
 */
abstract class Logger
{
    protected $filename;  // local do arquivo de LOG
    
    /*
     * método __construct()
     * instancia um logger
     * @param $filename = local do arquivo de LOG
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        // reseta o conteúdo do arquivo
        file_put_contents($filename, '');
    }
    
    // define o método write como obrigatório
    abstract function write($message);
}
