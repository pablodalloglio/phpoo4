<?php
Namespace Livro\Database;

use Exception;

/*
 * classe Repository
 * esta classe provê os métodos necessários para manipular coleções de objetos.
 */
final class Repository
{
    private $class; // nome da classe manipulada pelo repositório
    
    /* método __construct()
     * instancia um Repositório de objetos
     * @param $class = Classe dos Objetos
     */
    function __construct($class)
    {
        $this->class = $class;
    }
    
    /*
     * método load()
     * Recuperar um conjunto de objetos (collection) da base de dados
     * através de um critério de seleção, e instanciá-los em memória
     * @param $criteria = objeto do tipo TCriteria
     */
    function load(Criteria $criteria)
    {
        
        // instancia a instrução de SELECT
        $sql = new SqlSelect;
        $sql->addColumn('*');
        $sql->setEntity(constant($this->class.'::TABLENAME'));
        
        // atribui o critério passado como parâmetro
        $sql->setCriteria($criteria);
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // registra mensagem de log
            Transaction::log($sql->getInstruction());
            
            // executa a consulta no banco de dados
            $result= $conn->Query($sql->getInstruction());
            $results = array();
            
            if ($result)
            {
                // percorre os resultados da consulta, retornando um objeto
                while ($row = $result->fetchObject($this->class))
                {
                    // armazena no array $results;
                    $results[] = $row;
                }
            }
            return $results;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
    
    /*
     * método delete()
     * Excluir um conjunto de objetos (collection) da base de dados
     * através de um critério de seleção.
     * @param $criteria = objeto do tipo Criteria
     */
    function delete(Criteria $criteria)
    {
        // instancia instrução de DELETE
        $sql = new SqlDelete;
        $sql->setEntity(constant($this->class.'::TABLENAME'));
        
        // atribui o critério passado como parâmetro
        $sql->setCriteria($criteria);
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // registra mensagem de log
            Transaction::log($sql->getInstruction());
            // executa instrução de DELETE
            $result = $conn->exec($sql->getInstruction());
            return $result;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
            
        }
    }
    
    /*
     * método count()
     * Retorna a quantidade de objetos da base de dados
     * que satisfazem um determinado critério de seleção.
     * @param $criteria = objeto do tipo TCriteria
     */
    function count(Criteria $criteria)
    {
        
        // instancia instrução de SELECT
        $sql = new SqlSelect;
        $sql->addColumn('count(*)');
        $sql->setEntity(constant($this->class.'::TABLENAME'));
        
        // atribui o critério passado como parâmetro
        $sql->setCriteria($criteria);
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // registra mensagem de log
            Transaction::log($sql->getInstruction());
            
            // executa instrução de SELECT
            $result= $conn->Query($sql->getInstruction());
            if ($result)
            {
                $row = $result->fetch();
            }
            // retorna o resultado
            return $row[0];
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
}
?>