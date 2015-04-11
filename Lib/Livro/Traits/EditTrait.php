<?php
Namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;

trait EditTrait
{
    /**
     * Carrega registro para edição
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key']; // obtém a chave
                Transaction::open( $this->connection ); // inicia transação com o BD
                $class = $this->activeRecord;
                
                $object = new $class($key); // instancia o Active Record
                $this->form->setData($object); // lança os dados no formulário
                Transaction::close(); // finaliza a transação
            }
        }
        catch (Exception $e)
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', '<b>Erro</b>' . $e->getMessage());
            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
}
