<?php
Namespace Livro\Widgets\Container;

/**
 * classe Table
 * responsсvel pela exibiчуo de tabelas
 */
class Table extends Element
{
    /**
     * mщtodo construtor
     * instancia uma nova tabela
     */
    public function __construct()
    {
        parent::__construct('table');
    }
    
    /**
     * mщtodo addRow
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
