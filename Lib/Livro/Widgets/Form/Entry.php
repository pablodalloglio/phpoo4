<?php
namespace Livro\Widgets\Form;

/**
 * classe Entry
 * classe para construção de caixas de texto
 * @author Pablo Dall'Oglio
 */
class Entry extends Field implements FormElementInterface
{
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        // atribui as propriedades da TAG
        $this->tag->name = $this->name;     // nome da TAG
        $this->tag->value = $this->value;   // valor da TAG
        $this->tag->type = 'text';          // tipo de input
        $this->tag->style = "width:{$this->size}px"; // tamanho em pixels
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            $this->tag->readonly = "1";
        }
        // exibe a tag
        $this->tag->show();
    }
}
