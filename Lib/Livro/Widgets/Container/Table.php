<?php
Namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

/**
 * classe Table
 * responsável pela exibição de tabelas
 */
class Table extends Element
{
    /**
     * método construtor
     * instancia uma nova tabela
     */
    public function __construct()
    {
        parent::__construct('table');
    }
    
    /**
     * método addRow
     * agrega um novo objeto linha (TableRow) na tabela
     */
    public function addRow()
    {
        // instancia objeto linha
        $row = new TableRow;
        
        // armazena no array de linhas
        parent::add($row);
        return $row;
    }
}
