<?php
namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * Representa um rótulo de texto
 * @author Pablo Dall'Oglio
 */
class Label extends Field implements FormElementInterface
{
    private $embedStyle;
    protected $size;
    protected $id;
    
    /**
     * Construtor
     * @param $value text label
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
    
    /**
     * Adiciona conteúdo no label
     */
    public function add($child)
    {
        $this->tag->add($child);
    }
    
    /**
     * Exibe o widget
     */
    public function show()
    {
        $this->tag->add($this->value);
        $this->tag->show();
    }
}
