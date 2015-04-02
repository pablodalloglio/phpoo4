<?php
Namespace Livro\Widgets\Form;

/**
 * classe CheckButton
 * classe para construção de botões de verificação
 */
class CheckButton extends Field implements WidgetInterface
{
    public function setIndexValue($index)
    {        
        $this->indexValue = $index;
    }
    
    /**
     * método show()
     * exibe o widget na tela
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
