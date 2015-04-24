<?php
namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;

/**
 * Representa uma caixa de texto
 * @author Pablo Dall'Oglio
 */
class Text extends Field implements FormElementInterface
{
    private $width;
    private $height;
    
    /**
     * Instancia um novo objeto
     * @param $name = nome do campo
     */
    public function __construct($name)
    {
        // executa o método construtor da classe-pai.
        parent::__construct($name);
        
        // cria uma tag HTML do tipo <textarea >
        $this->tag = new Element('textarea');
        $this->tag->class = 'field';        // classe CSS
        
        // define a altura padrão da caixa de texto
        $this->height= 100;
    }
    
    /**
     * Define o tamanho de um campo de texto
     * @param $width  = largura
     * @param $height = altura
     */
    public function setSize($width, $height = NULL)
    {
        $this->size    = $width;
        if (isset($height))
        {
            $this->height = $height;
        }
    }
    
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        $this->tag->name = $this->name; // nome da TAG
        $this->tag->style = "width:{$this->size};height:{$this->height}"; // tamanho em pixels
        
        // se o campo não é editável
        if (!parent::getEditable())
        {
            // desabilita a TAG input
            $this->tag->readonly = "1";
        }
        
        // adiciona conteúdo ao textarea
        $this->tag->add(htmlspecialchars($this->value));
        
        // exibe a tag
        $this->tag->show();
    }
}
