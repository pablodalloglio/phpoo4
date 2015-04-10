<?php
Namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * Representa um campo de um formulário
 * @author Pablo Dall'Oglio
 */
abstract class Field implements FormElementInterface
{
    protected $name;
    protected $size;
    protected $value;
    protected $editable;
    protected $tag;
    protected $validations;
    
    /**
     * Instancia um campo do formulario
     * @param $name = nome do campo
     */
    public function __construct($name)
    {
        // define algumas características iniciais
        self::setEditable(true);
        self::setName($name);
        self::setSize(200);
        
        // cria uma tag HTML do tipo <input>
        $this->tag = new Element('input');
        $this->tag->class = 'tfield';		  // classe CSS
    }
    
    /**
     * Define o nome do widget
     * @param $name     = nome do widget
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Retorna o nome do widget
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Define o valor de um campo
     * @param $value    = valor do campo
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * Retorna o valor de um campo
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Define se o campo poderá ser editado
     * @param $editable = TRUE ou FALSE
     */
    public function setEditable($editable)
    {
        $this->editable= $editable;
    }
    
    /**
     * Retorna o valor da propriedade $editable
     */
    public function getEditable()
    {
        return $this->editable;
    }
    
    /**
     * Define uma propriedade para o campo
     * @param $name = nome da propriedade
     * @param $valor = valor da propriedade
     */
    public function setProperty($name, $value)
    {
        // define uma propriedade de $this->tag
        $this->tag->$name = $value;
    }
    
    /**
     * Define a largura do widget
     * @param $width = largura em pixels
     * @param $height = altura em pixels (usada em TText)
     */
    public function setSize($width, $height = NULL)
    {
        $this->size = $width;
    }
    
    /**
     * Adiciona um validador para o campo
     * @param $label nome do campo
     * @param $validator Validador TFieldValidator
     * @param $parameters Parâmetros adicionais
     */
    public function addValidation($label, IFieldValidator $validator, $parameters = NULL)
    {
        $this->validations[] = array($label, $validator, $parameters);
    }
    
    /**
     * Valida o campo
     */
    public function validate()
    {
        if ($this->validations)
        {
            foreach ($this->validations as $validation)
            {
                $label      = $validation[0];
                $validator  = $validation[1];
                $parameters = $validation[2];
                
                $validator->validate($label, $this->getValue(), $parameters);
            }
        }
    }
}
