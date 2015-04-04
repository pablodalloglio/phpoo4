<?php
Namespace Adianti\Widget\Container;

use Livro\Widgets\Base\Element;

/**
 * Vertical Box
 *
 * @version    2.0
 * @package    widget
 * @subpackage container
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class VBox extends Element
{
    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct('div');
        $this->{'style'} = 'display: inline-block';
    }
    
    /**
     * Add an child element
     * @param $child Any object that implements the show() method
     */
    public function add($child)
    {
        $wrapper = new TElement('div');
        $wrapper->{'style'} = 'clear:both';
        $wrapper->add($child);
        parent::add($wrapper);
        return $wrapper;
    }
}
