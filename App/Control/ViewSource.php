<?php
use Livro\Control\Page;

use Bootstrap\Widgets\Panel;

/**
 * Exibe código-fonte
 */
class ViewSource extends Page
{
    private $form; // formulário
    
    public function onView($param)
    {
        $class = $param['source'];
        $file = "App/Control/{$class}.php";
        if (file_exists( $file ))
        {
            $panel = new Panel('Código-fonte');
            $panel->add( highlight_file($file, TRUE) );
            
            parent::add($panel);
        }
    }
}
