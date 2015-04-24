<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

/**
 * Representa uma tabela
 * @author Pablo Dall'Oglio
 */
class Table extends Element
{
    /**
     * Instancia uma nova tabela
     */
    public function __construct()
    {
        parent::__construct('table');
    }
    
    /**
     * Agrega um novo objeto linha (TableRow) na tabela
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
