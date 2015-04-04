<?php
Namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

/**
 * classe Image
 * classe para exibição de imagens
 */
class Image extends Element
{
    private $source; // localização da imagem
    
    /**
     * método construtor
     * instancia objeto TImage
     * @param $source = localização da imagem
     */
    public function __construct($source)
    {
        parent::__construct('img');
        
        // atribui a localização da imagem
        $this->src = $source;
        $this->border = 0;
    }
}
