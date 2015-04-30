<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

/**
 * Representa a paginação de uma datagrid
 * @author Pablo Dall'Oglio
 */
class PageNavigation
{
    private $action;
    private $pageSize;
    private $currentPage;
    private $totalRecords;
    
    public function __construct()
    {
        $this->pageSize = 10;
    }
    
    function setAction(Action $action)
    {
        $this->action = $action;
    }
    
    function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }
    
    function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }
    
    function setTotalRecords($totalRecords)
    {
        $this->totalRecords = $totalRecords;
    }
    
    function show()
    {
        $pages = ceil($this->totalRecords / $this->pageSize);
        
        echo '<ul class="pagination">';
        for ($n=1; $n <= $pages; $n++)
        {
            $offset = ($n -1) * $this->pageSize;
            
            $action = $this->action;
            $action->setParameter('offset', $offset);
            $action->setParameter('page',   $n);
            
            $url = $action->serialize();
            $class = ($this->currentPage == $n) ? 'active' : '';
            
            echo "<li class='{$class}'>";
            echo "<a href='$url'>{$n}</a>&nbsp;&nbsp;";
            echo '</li>';
            
        }
        echo '</ul>';
    }
}
