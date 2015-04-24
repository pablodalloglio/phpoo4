<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

/**
 * Representa uma ação de uma datagrid
 * @author Pablo Dall'Oglio
 */
class DatagridAction extends Action
{
    private $image;
    private $label;
    private $field;
    
    /**
     * Atribui uma imagem à ação
     * @param $image = local do arquivo de imagem
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    
    /**
     * Retorna a imagem da ação
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Define o rótulo de texto da ação
     * @param $label = rótulo de texto da ação
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
    
    /**
     * Retorna o rótulo de texto da ação
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * Define o nome do campo que será passado juntamente com a ação
     * @param $field = nome do campo do banco de dados
     */
    public function setField($field)
    {
        $this->field = $field;
    }
    
    /**
     * Retorna o nome do campo definido pelo método setField()
     */
    public function getField()
    {
        return $this->field;
    }
}
