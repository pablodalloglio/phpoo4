<?php
namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * Representa uma combo box
 * @author Pablo Dall'Oglio
 */
class Combo extends Field implements FormElementInterface
{
    private $items; // array contendo os itens da combo
    protected $properties;
    
    /**
     * Adiciona items à combo box
     * @param $items = array de itens
     */
    public function addItems($items)
    {
        $this->items = $items;
    }
    
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        $tag = new Element('select');
        $tag->class = 'combo';
        $tag->name = $this->name;
        $tag->style = "width:{$this->size}"; // tamanho em pixels
        
        // cria uma TAG <option> com um valor padrão
        $option = new Element('option');
        $option->add('');
        $option->value = '0';    // valor da TAG
        
        // adiciona a opção à combo
        $tag->add($option);
        if ($this->items)
        {
            // percorre os itens adicionados
            foreach ($this->items as $chave => $item)
            {
                // cria uma TAG <option> para o item
                $option = new Element('option');
                $option->value = $chave; // define o índice da opção
                $option->add($item);     // adiciona o texto da opção
                
                // caso seja a opção selecionada
                if ($chave == $this->value)
                {
                    // seleciona o item da combo
                    $option->selected = 1;
                }
                // adiciona a opção à combo
                $tag->add($option);
            }
        }
        
        // verifica se o campo é editável
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
        
        // exibe a combo
        $tag->show();
    }
}
