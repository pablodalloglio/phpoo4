<?php
Namespace Livro\Widgets\Container;

/**
 * classe TableCell
 * reponsсvel pela exibiчуo de uma cщlula de uma tabela
 */
class TableCell extends Element
{
    /**
     * mщtodo construtor
     * instancia uma nova cщlula
     * @param $value = conteњdo da cщlula
     */
    public function __construct($value)
    {
        parent::__construct('td');
        parent::add($value);
    }
}
