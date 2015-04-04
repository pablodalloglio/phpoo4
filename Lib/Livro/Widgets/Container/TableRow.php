<?php
Namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

/**
 * classe TableRow
 * reponsável pela exibição de uma linha de uma tabela
 */
class TableRow extends Element
{
    /**
     * método construtor
     * instancia uma nova linha
     */
    public function __construct()
    {
        parent::__construct('tr');
    }
    
    /**
     * método addCell
     * agrega um novo objeto célula (TTableCell) à linha
     * @param $value = conteúdo da célula
     */
    public function addCell($value)
    {
        // instancia objeto célula
        $cell = new TableCell($value);
        parent::add($cell);
        
        // retorna o objeto instanciado
        return $cell;
    }
}
