<?php
Namespace Livro\Widgets\Form;

/**
 * classe TField
 * classe base para construção dos widgets para formuláros
 */
abstract class Field
{
    protected $name;
    protected $size;
    protected $value;
    protected $editable;
    protected $tag;
    protected $validations;
    
    /**
     * método construtor
     * instancia um campo do formulario
     * @param $name = nome do campo
     */
    public function __construct($name)
    {
        // define algumas características iniciais
        self::setEditable(true);
        self::setName($name);
        self::setSize(200);
        
        // Instancia um estilo CSS chamado tfield
        // que será utilizado pelos campos do formulário
        $style1 = new TStyle('tfield');
        $style1->border          = 'solid';
        $style1->border_color    = '#a0a0a0';
        $style1->border_width    = '1px';
        $style1->z_index         = '1';
        $style2 = new TStyle('tfield_disabled');
        $style2->border          = 'solid';
        $style2->border_color    = '#a0a0a0';
        $style2->border_width    = '1px';
        $style2->background_color= '#e0e0e0';
        $style2->color           = '#a0a0a0';
        $style1->show();
        $style2->show();
        
        // cria uma tag HTML do tipo <input>
        $this->tag = new TElement('input');
        $this->tag->class = 'tfield';		  // classe CSS
    }
    
    /**
     * método setName()
     * define o nome do widget
     * @param $name     = nome do widget
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * método getName()
     * retorna o nome do widget
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * método setValue()
     * define o valor de um campo
     * @param $value    = valor do campo
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * método getValue()
     * retorna o valor de um campo
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * método setEditable()
     * define se o campo poderá ser editado
     * @param $editable = TRUE ou FALSE
     */
    public function setEditable($editable)
    {
        $this->editable= $editable;
    }
    
    /**
     * método getEditable()
     * retorna o valor da propriedade $editable
     */
    public function getEditable()
    {
        return $this->editable;
    }
    
    /**
     * método setProperty()
     * define uma propriedade para o campo
     * @param $name = nome da propriedade
     * @param $valor = valor da propriedade
     */
    public function setProperty($name, $value)
    {
        // define uma propriedade de $this->tag
        $this->tag->$name = $value;
    }
    
    /**
     * método setSize()
     * define a largura do widget
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
