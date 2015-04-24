<?php
namespace Livro\Widgets\Container;

use Livro\Widgets\Base\Element;

/**
 * Empacota elementos em painel Bootstrap
 * @author Pablo Dall'Oglio
 */
class Panel extends Element
{
    private $body;
    
    /**
     * Constrói o painel
     */
    public function __construct($panel_title = NULL)
    {
        parent::__construct('div');
        $this->class = 'panel panel-default';
        
        if ($panel_title)
        {
            $head = new Element('div');
            $head->class = 'panel-heading';
        
            $label = new Element('h4');
            $label->add($panel_title);
            
            $title = new Element('div');
            $title->class = 'panel-title';
            $title->add( $label );
            $head->add($title);
            parent::add($head);
        }
        
        $this->body = new Element('div');
        $this->body->class = 'panel-body';
        parent::add($this->body);
        
    }
    
    /**
     * Adiciona conteúdo
     */
    public function add($content)
    {
        $this->body->add($content);
    }
}
