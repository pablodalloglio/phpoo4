<?php
namespace Livro\Database;

/**
 * Implementa o algoritmo de LOG em TXT
 * @author Pablo Dall'Oglio
 */
class LoggerTXT extends Logger
{
    /**
     * Escreve uma mensagem no arquivo de LOG
     * @param $message = mensagem a ser escrita
     */
    public function write($message)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $time = date("Y-m-d H:i:s");
        
        // monta a string
        $text = "$time :: $message\n";
        
        // adiciona ao final do arquivo
        $handler = fopen($this->filename, 'a');
        fwrite($handler, $text);
        fclose($handler);
    }
}
