<?php
Namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

/**
 * classe TableCell
 * reponsável pela exibição de uma célula de uma tabela
 */
class TableCell extends Element
{
    /**
     * método construtor
     * instancia uma nova célula
     * @param $value = conteúdo da célula
     */
    public function __construct($value)
    {
        parent::__construct('td');
        parent::add($value);
    }
}
