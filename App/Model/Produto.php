<?php
use Livro\Database\Record;

class Produto extends Record
{
    const TABLENAME = 'produto';
    
    private $fabricante;
    
    /**
     * Retorna o nome do fabricante do produto.
     * Executado sempre se for acessada a propriedade "->nome_fabricante"
     */
    public function get_nome_fabricante()
    {
        if (empty($this->fabricante))
        {
            $this->fabricante = new Fabricante($this->id_fabricante);
        }
        return $this->fabricante->nome;
    }
}
