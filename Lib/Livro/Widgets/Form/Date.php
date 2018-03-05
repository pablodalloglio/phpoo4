<?php
namespace Livro\Widgets\Form;

/**
 * classe Date
 * classe para construção de caixas de texto
 * @author Pablo Dall'Oglio
 */
class Date extends Entry implements FormElementInterface
{
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        $this->tag->name = $this->name;     // nome da TAG
        $this->tag->value = $this->value;   // valor da TAG
        $this->tag->type = 'date';          // tipo de input
        $this->tag->style = "width:{$this->size}"; // tamanho em pixels
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            $this->tag->readonly = "1";
        }
        // exibe a tag
        $this->tag->show();
    }
}
