<?php
/*
 * classe ClienteServices
 * Remote Facade para cadastro de Clientes
 */
class ClienteServices
{
    /*
     * método salvar()
     * recebe um array com dados de cliente e armazena no banco de dados
     */
    function salvar($dados)
    {
        try
        {
            // inicia transação com o banco 'pg_livro'
            TTransaction::open('pg_livro');
            // define um arquivo de log
            TTransaction::setLogger(new TLoggerTXT('/tmp/log.txt'));
            // instancia um Active Record para cliente
            
            $cliente = new Cliente;
            
            // alimenta o registro com dados do array
            
            $cliente->fromArray($dados);
            
            $cliente->store();		   // armazena o objeto
            
            // fecha transação
            
            TTransaction::close();
            
        }
        catch (Exception $e)
        {
            // caso ocorra erros, volta a transação
            TTransaction::rollback();
            // retorna o erro na forma de um objeto SoapFault
            return new SoapFault("Server", $e->getMessage());
        }
    }
}
?>