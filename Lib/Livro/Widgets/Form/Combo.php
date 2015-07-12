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
    
    /**
     * Instancia a combo box
     * @param $name = nome do campo
     */
    public function __construct($name)
    {
        // executa o método construtor da classe-pai.
        parent::__construct($name);
        
        // cria uma tag HTML do tipo <select>
        $this->tag = new Element('select');
        $this->tag->class = 'combo';       // classe CSS
    }
    
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
        // atribui as propriedades da TAG
        $this->tag->name = $this->name;      // nome da TAG
        $this->tag->style = "width:{$this->size}px"; // tamanho em pixels
        
        // cria uma TAG <option> com um valor padrão
        $option = new Element('option');
        $option->add('');
        $option->value = '0';    // valor da TAG
        
        // adiciona a opção à combo
        $this->tag->add($option);
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
                $this->tag->add($option);
            }
        }
        
        // verifica se o campo é editável
        if (!parent::getEditable())
        {
            // desabilita a TAG input
            $this->tag->readonly = "1";
        }
        
        // exibe a combo
        $this->tag->show();
    }
}
