<?php
Namespace Bootstrap\Wrapper;

/**
 * Decora datagrids no formato Bootstrap
 */
class DatagridWrapper
{
    private $decorated;
    
    /**
     * Constrói o decorator
     */
    public function __construct($datagrid)
    {
        $this->decorated = $datagrid;
        $this->decorated->class = 'table table-striped';
    }
    
    /**
     * Redireciona chamadas para o objeto decorado
     */
    public function __call($method, $parameters)
    {
        call_user_func_array(array($this->decorated, $method),$parameters);
    }
}
