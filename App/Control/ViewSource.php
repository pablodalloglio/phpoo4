<?php
use Livro\Control\Page;

use Livro\Widgets\Container\Panel;

/**
 * Exibe código-fonte
 */
class ViewSource extends Page
{
    private $form; // formulário
    
    public function onView($param)
    {
    
        ini_set('highlight.comment', "#808080");
        ini_set('highlight.default', "#FFFFFF");
        ini_set('highlight.html',    "#C0C0C0");
        ini_set('highlight.keyword', "#62d3ea");
        ini_set('highlight.string',  "#FFC472");
        
        $class = $param['source'];
        $file = "App/Control/{$class}.php";
        if (file_exists( $file ))
        {
            $panel = new Panel('Código-fonte: '. $class);
            $panel->id = 'source-panel';
            $panel->add( highlight_file($file, TRUE) );
            
            parent::add($panel);
        }
    }
}
