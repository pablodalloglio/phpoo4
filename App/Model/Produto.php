<?php
use Livro\Database\Record;

class Produto extends Record
{
    const TABLENAME = 'produto';
    
    private $fabricante;
    
    /**
     * Retorna o nome do fabricante do produto
     */
    function get_nome_fabricante()
    {
        $this->fabricante = new Fabricante($this->id_fabricante);
        return $this->fabricante->nome;
        
    }
    
}