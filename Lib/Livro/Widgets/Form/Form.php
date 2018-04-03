<?php
namespace Livro\Widgets\Form;

use Livro\Control\ActionInterface;

/**
 * Representa um formulário
 * @author Pablo Dall'Oglio
 */
class Form
{
    protected $title;
    protected $name;
    protected $fields;
    protected $actions;
    
    /**
     * Instancia o formulário
     * @param $name = nome do formulário
     */
    public function __construct($name = 'my_form')
    {
        $this->setName($name);
    }
    
    /**
     * Define o nome do formulário
     * @param $name = nome do formulário
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Retorna o nome do formulário
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Define o título do formulário
     * @param $title Título
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Retorna o título do formulário
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Add a form field
     * @param $label     Field Label
     * @param $object    Field Object
     * @param $size      Field Size
     */
    public function addField($label, FormElementInterface $object, $size = '100%')
    {
        $object->setSize($size);
        $object->setLabel($label);
        $this->fields[$object->getName()] = $object;
    }
    
    /**
     * Adiciona uma ação
     * @param $label  Action Label
     * @param $action TAction Object
     */
    public function addAction($label, ActionInterface $action)
    {
        $this->actions[$label] = $action;
    }
    
    /**
     * Retorna os campos
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    /**
     * Retorna as ações
     */
    public function getActions()
    {
        return $this->actions;
    }
    
    /**
     * Atribui dados aos campos do formulário
     * @param $object = objeto com dados
     */
    public function setData($object)
    {
        foreach ($this->fields as $name => $field)
        {
            if ($name AND isset($object->$name))
            {
                $field->setValue($object->$name);
            }
        }
    }
    
    /**
     * Retorna os dados do formulário em forma de objeto
     */
    public function getData($class = 'stdClass')
    {
        $object = new $class;
        
        foreach ($this->fields as $key => $fieldObject)
        {
            $val = isset($_POST[$key]) ? $_POST[$key] : '';
            $object->$key = $val;
        }
        // percorre os arquivos de upload
        foreach ($_FILES as $key => $content)
        {
            $object->$key = $content['tmp_name'];
        }
        return $object;
    }
}
