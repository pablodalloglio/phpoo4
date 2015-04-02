<?php
Namespace Livro\Widgets\Form;

/**
 * classe Password
 * classe para construção de campos de digitação de senhas
 */
class Password extends Field implements WidgetInterface
{
    /**
     * método show()
     * exibe o widget na tela
     */
    public function show()
    {
        // atribui as propriedades da TAG
        $this->tag->name = $this->name; // nome da TAG
        $this->tag->value = $this->value; // valor da TAG
        $this->tag->type = 'password';          // tipo do input
        $this->tag->style = "width:{$this->size}";		 // tamanho em pixels
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            $this->tag->readonly = "1";
            $this->tag->class = 'tfield_disabled';		           // classe CSS
        }
        // exibe a tag
        $this->tag->show();
    }
}
