<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

/**
 * Representa uma coluna de uma datagrid
 * @author Pablo Dall'Oglio
 */
class DatagridColumn
{
    private $name;
    private $label;
    private $align;
    private $width;
    private $action;
    private $transformer;
    
    /**
     * Instancia uma coluna nova
     * @param $name = nome da coluna no banco de dados
     * @param $label = rótulo de texto que será exibido
     * @param $align = alinhamento da coluna (left, center, right)
     * @param $width = largura da coluna (em pixels)
     */
    public function __construct($name, $label, $align, $width)
    {
        // atribui os parâmetros às propriedades do objeto
        $this->name = $name;
        $this->label = $label;
        $this->align = $align;
        $this->width = $width;
    }
    
    /**
     * Retorna o nome da coluna no banco de dados
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Retorna o nome do rótulo de texto da coluna
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * Retorna o alinhamento da coluna (left, center, right)
     */
    public function getAlign()
    {
        return $this->align;
    }
    
    /**
     * Retorna a largura da coluna (em pixels)
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * Define uma ação a ser executada quando o usuário sclicar sobre o título da coluna
     * @param $action = objeto TAction contendo a ação
     */
    public function setAction(Action $action)
    {
        $this->action = $action;
    }
    
    /**
     * Retorna a ação vinculada à coluna
     */
    public function getAction()
    {
        // verifica se a coluna possui ação
        if ($this->action)
        {
            return $this->action->serialize();
        }
    }
    
    /**
     * Define uma função (callback) a ser aplicada sobre a coluna
     * @param $callback = função do PHP ou do usuário
     */
    public function setTransformer($callback)
    {
        $this->transformer = $callback;
    }
    
    /**
     * Retorna a função (callback) aplicada à coluna
     */
    public function getTransformer()
    {
        return $this->transformer;
    }
}
