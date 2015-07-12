<?php
namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\Table;
use Livro\Control\ActionInterface;
use Livro\Widgets\Container\HBox;

/**
 * Representa um formulário
 * @author Pablo Dall'Oglio
 */
class Form extends Element
{
    protected $fields;      // array de objetos contidos pelo form
    protected $actions;
    protected $table;
    private $has_action;
    private $actions_container;
    
    /**
     * Instancia o formulário
     * @param $name = nome do formulário
     */
    public function __construct($name = 'my_form')
    {
        parent::__construct('form');
        $this->enctype = "multipart/form-data";
        $this->method  = 'post';    // método de transferência
        $this->setName($name);
        
        $this->table = new Table;
        $this->table->width = '100%';
        parent::add($this->table);
    }
    
    /**
     * Define o nome do formulário
     * @param $name      = nome do formulário
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
    public function setFormTitle($title)
    {
        // add the field to the container
        $row = $this->table->addRow();
        $row->{'class'} = 'form-title';
        $cell = $row->addCell( $title );
        $cell->{'colspan'} = 2;
    }
    
    /**
     * Add a form field
     * @param $label     Field Label
     * @param $object    Field Object
     * @param $size      Field Size
     */
    public function addField($label, FormElementInterface $object, $size = 200)
    {
        $object->setSize($size, $size);
        $this->fields[$object->getName()] = $object;
        $object->setLabel($label);
        
        // adiciona linha
        $row = $this->table->addRow();
        
        $label_field = new Label($label);
        
        if ($object instanceof Hidden)
        {
            $row->addCell( '' );
        }
        else
        {
            $row->addCell( $label_field );
        }
        $row->addCell( $object );
        
        return $row;
    }
    
    /**
     * Adiciona uma ação
     * @param $label  Action Label
     * @param $action TAction Object
     */
    public function addAction($label, ActionInterface $action)
    {
        $name   = strtolower(str_replace(' ', '_', $label));
        $button = new Button($name);
        //$this->fields[] = $button;
        
        $button->setFormName($this->name);
        
        // define the button action
        $button->setAction($action, $label);
        
        if (!$this->has_action)
        {
            $this->actions_container = new HBox;
            
            $row  = $this->table->addRow();
            $row->{'class'} = 'formaction';
            $cell = $row->addCell( $this->actions_container );
            $cell->colspan = 2;
        }
        
        // add cell for button
        $this->actions_container->add($button);
        
        $this->has_action = TRUE;
        $this->actions[] = $button;
        
        return $button;
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
            $val = isset($_POST[$key])? $_POST[$key] : '';
            if (!$fieldObject instanceof Button)
            {
                $object->$key = $val;
            }
        }
        // percorre os arquivos de upload
        foreach ($_FILES as $key => $content)
        {
            $object->$key = $content['tmp_name'];
        }
        return $object;
    }
}
