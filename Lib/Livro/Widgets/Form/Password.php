<?php
namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * Representa um campo para digitação de senhas
 * @author Pablo Dall'Oglio
 */
class Password extends Field implements FormElementInterface
{
    protected $properties;
    
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        // atribui as propriedades da TAG
        $tag = new Element('input');
        $tag->class = 'field';		  // classe CSS
        $tag->name = $this->name; // nome da TAG
        $tag->value = $this->value; // valor da TAG
        $tag->type = 'password';          // tipo do input
        $tag->style = "width:{$this->size}"; // tamanho em pixels
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            $tag->readonly = "1";
        }
        
        if ($this->properties)
        {
            foreach ($this->properties as $property => $value)
            {
                $tag->$property = $value;
            }
        }
        
        // exibe a tag
        $tag->show();
    }
}
