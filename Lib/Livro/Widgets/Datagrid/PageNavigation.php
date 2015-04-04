<?php
Namespace Livro\Widgets\Datagrid;

/**
 * classe PageNavigation
 * Cria uma barra de navegação para um TDataGrid
 */
class PageNavigation
{
    private $action;
    private $pageSize;
    private $currentPage;
    private $totalRecords;
    
    /**
     * método ()
     *
     * @param  $xxxx    = xxxx
     */
    function setAction(Action $action)
    {
        $this->action = $action;
    }
    
    /**
     * método ()
     *
     * @param  $xxxx    = xxxx
     */
    function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }
    
    /**
     * método ()
     *
     * @param  $xxxx    = xxxx
     */
    function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }
    
    /**
     * método ()
     *
     * @param  $xxxx    = xxxx
     */
    function setTotalRecords($totalRecords)
    {
        $this->totalRecords = $totalRecords;
    }
    
    /**
     * método ()
     *
     * @param  $xxxx    = xxxx
     */
    function show()
    {
        $pages = ceil($this->totalRecords / $this->pageSize);
        
        for ($n=1; $n <= $pages; $n++)
        {
            $offset = ($n -1) * $this->pageSize;
            
            $action = $this->action;
            $action->setParameter('offset', $offset);
            $action->setParameter('page',   $n);
            
            $url = $action->serialize();
            $label = ($this->currentPage == $n) ? "<u><b>$n</b></u>" : $n;
            echo "<a href='$url'>{$label}</a>&nbsp;&nbsp;";
        }
    }
}
