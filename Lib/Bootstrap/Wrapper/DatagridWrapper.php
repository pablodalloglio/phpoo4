<?php
Namespace Bootstrap\Wrapper;
use Livro\Widgets\Container\Table;

/**
 * Decora datagrids no formato Bootstrap
 */
class DatagridWrapper
{
    private $decorated;
    
    /**
     * Constrói o decorator
     */
    public function __construct(Table $datagrid)
    {
        $this->decorated = $datagrid;
        $this->decorated->class = 'table table-striped table-hover';
    }
    
    /**
     * Redireciona chamadas para o objeto decorado
     */
    public function __call($method, $parameters)
    {
        call_user_func_array(array($this->decorated, $method),$parameters);
    }
}
