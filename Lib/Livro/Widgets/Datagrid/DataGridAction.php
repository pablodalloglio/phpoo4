<?php
Namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

/**
 * class TDataGridAction
 * representa uma ação de uma listagem
 */
class DataGridAction extends Action
{
    private $image;
    private $label;
    private $field;
    
    /**
     * método setImage()
     * atribui uma imagem à ação
     * @param $image = local do arquivo de imagem
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    
    /**
     * método getImage()
     * retorna a imagem da ação
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * método setLabel()
     * define o rótulo de texto da ação
     * @param $label = rótulo de texto da ação
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
    
    /**
     * método getLabel()
     * retorna o rótulo de texto da ação
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * método setField()
     * define o nome do campo do banco de dados que será passado juntamente com a ação
     * @param $field = nome do campo do banco de dados
     */
    public function setField($field)
    {
        $this->field = $field;
    }
    
    /**
     * método getField()
     * retorna o nome do campo de dados definido pelo método setField()
     */
    public function getField()
    {
        return $this->field;
    }
}
