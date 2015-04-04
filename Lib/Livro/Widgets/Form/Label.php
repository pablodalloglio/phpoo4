<?php
Namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * classe Label
 * classe para construção de rótulos de texto
 */
class Label extends Field implements FormElementInterface
{
    private $embedStyle;
    protected $size;
    protected $id;
    
    /**
     * Class Constructor
     * @param  $value text label
     */
    public function __construct($value)
    {
        $this->id = uniqid();
        $stylename = 'tlabel_'.$this->id;
        
        // set the label's content
        $this->setValue($value);
        
        // create a new element
        $this->tag = new Element('label');
    }
    
    public function add($value)
    {
        $this->tag->add($value);
    }
    
    public function show()
    {
        $this->tag->add($this->value);
        $this->tag->show();
    }
}
