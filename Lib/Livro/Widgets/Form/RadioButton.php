<?php
namespace Livro\Widgets\Form;

/**
 * Representa um RadioButton
 * @author Pablo Dall'Oglio
 */
class RadioButton extends Field implements FormElementInterface
{
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        // atribui as propriedades da TAG
        $this->tag->name = $this->name;
        $this->tag->value = $this->value;
        $this->tag->type = 'radio';
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            // desabilita a TAG input
            $this->tag->readonly = "1";
        }
        // exibe a tag
        $this->tag->show();
    }
}
