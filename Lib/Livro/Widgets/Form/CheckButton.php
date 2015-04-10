<?php
Namespace Livro\Widgets\Form;

/**
 * Representa botões de verificação
 * @author Pablo Dall'Oglio
 */
class CheckButton extends Field implements FormElementInterface
{
    /**
     * Define o valor a ser postado
     */
    public function setIndexValue($index)
    {        
        $this->indexValue = $index;
    }
    
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        // atribui as propriedades da TAG
        $this->tag->name = $this->name;     // nome da TAG
        $this->tag-> value = $this->indexValue;   // value
        $this->tag->type = 'checkbox';      // tipo do input
        
        // compare current value with indexValue
        if ($this->indexValue == $this->value)
        {
            $this->tag-> checked= '1';
        }
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            // desabilita a TAG input
            $this->tag->readonly = "1";
            $this->tag->class = 'tfield_disabled'; // classe CSS
        }
        // exibe a tag
        $this->tag->show();
    }
}
