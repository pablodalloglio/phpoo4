<?php
Namespace Livro\Widget\Container;

use Livro\Widgets\Base\Element;

/**
 * Horizontal Box
 *
 * @version    2.0
 * @package    widget
 * @subpackage container
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class HBox extends Element
{
    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct('div');
    }
    
    /**
     * Add an child element
     * @param $child Any object that implements the show() method
     */
    public function add($child)
    {
        $wrapper = new Element('div');
        $wrapper->{'style'} = 'display:inline-block;';
        $wrapper->add($child);
        parent::add($wrapper);
        return $wrapper;
    }
}
