<?php
use Livro\Control\Page;
use Livro\Widgets\Container\HBox;

/**
 * Vendas por mês
 */
class DashboardView extends Page
{
    /**
     * método construtor
     */
    public function __construct()
    {
        parent::__construct();
        
        $hbox = new HBox;
        $hbox->add( new VendasMesChart )->style.=';width:48%;';
        $hbox->add( new VendasTipoChart )->style.=';width:48%';
        
        parent::add($hbox);
    }
}
