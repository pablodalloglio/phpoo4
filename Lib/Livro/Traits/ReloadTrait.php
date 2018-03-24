<?php
namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Widgets\Dialog\Message;

use Exception;

trait ReloadTrait
{
    /**
     * Carrega a DataGrid com os objetos
     */
    function onReload()
    {
        try
        {
            Transaction::open( $this->connection );
            $repository = new Repository( $this->activeRecord );
            // cria um critério de seleção de dados
            $criteria = new Criteria;
            $criteria->setProperty('order', 'id');
            
            if (isset($this->filters))
            {
                foreach ($this->filters as $filter)
                {
                    $criteria->add($filter[0], $filter[1], $filter[2], $filter[3]);
                }
            }
            
            // carreta os objetos que satisfazem o critério
            $objects = $repository->load($criteria);
            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    // adiciona o objeto na DataGrid
                    $this->datagrid->addItem($object);
                }
            }
            Transaction::close();
        }
        catch (Exception $e)
        {
            new Message($e->getMessage());
        }
    }
}
