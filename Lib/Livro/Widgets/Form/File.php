<?php
namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * Representa um componente de upload de arquivo
 * @author Pablo Dall'Oglio
 */
class File extends Field implements FormElementInterface
{
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        // atribui as propriedades da TAG
        $tag = new Element('input');
        $tag->class = 'field';		  
        $tag->name = $this->name;    // nome da TAG
        $tag->value = $this->value;  // valor da TAG
        $tag->type = 'file';         // tipo de input
        $tag->style = "width:{$this->size}"; // tamanho em pixels
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            // desabilita a TAG input
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
