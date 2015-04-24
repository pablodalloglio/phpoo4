<?php
namespace Livro\Traits;

use Livro\Control\Action;
use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;

trait DeleteTrait
{
    /**
     * Pergunta sobre a exclusão de registro
     */
    function onDelete($param)
    {
        $key = $param['key']; // obtém o parâmetro $key
        $action1 = new Action(array($this, 'Delete'));
        $action1->setParameter('key', $key);
        
        new Question('Deseja realmente excluir o registro?', $action1);
    }

    /**
     * Exclui um registro
     */
    function Delete($param)
    {
        try
        {
            $key = $param['key']; // obtém a chave
            Transaction::open( $this->connection ); // inicia transação com o BD
            
            $class = $this->activeRecord;
            
            $object = new $class($key); // instancia objeto
            $object->delete(); // deleta objeto do banco de dados
            Transaction::close(); // finaliza a transação
            $this->onReload(); // recarrega a datagrid
            new Message('info', "Registro excluído com sucesso");
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
        }
    }
}
