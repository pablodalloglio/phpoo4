<?php
namespace Livro\Widgets\Form;

/**
 * Representa botões de verificação
 * @author Pablo Dall'Oglio
 */
class CheckButton extends Field implements FormElementInterface
{
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        // atribui as propriedades da TAG
        $this->tag->name = $this->name;     // nome da TAG
        $this->tag->value = $this->value;   // value
        $this->tag->type = 'checkbox';      // tipo do input
        
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
