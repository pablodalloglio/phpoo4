<?php
namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * Representa um grupo de CheckButtons
 * @author Pablo Dall'Oglio
 */
class CheckGroup extends Field implements FormElementInterface
{
    private $layout = 'vertical';
    private $items;
    
    /**
     * Define a direção das opções (vertical ou horizontal)
     */
    public function setLayout($dir)
    {
        $this->layout = $dir;
    }
    
    /**
     * Adiciona itens ao check group
     * @param $items = um vetor indexado de itens
     */
    public function addItems($items)
    {
        $this->items = $items;
    }
    
    /**
     * exibe o widget na tela
     */
    public function show()
    {
        if ($this->items)
        {
            // percorre cada uma das opções do rádio
            foreach ($this->items as $index => $label)
            {
                $button = new CheckButton("{$this->name}[]");
                $button->setValue($index);
                
                // verifica se deve ser marcado
                if (in_array($index, (array) $this->value))
                {
                    $button->setProperty('checked', '1');
                }
                
                $obj = new Label($label);
                $obj->add($button);
                $obj->show();
                if ($this->layout == 'vertical')
                {
                    // exibe uma tag de quebra de linha
                    $br = new Element('br');
                    $br->show();
                    echo "\n";
                }
            }
        }
    }
}
