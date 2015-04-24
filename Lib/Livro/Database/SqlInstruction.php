<?php
namespace Livro\Database;

use Exception;

/**
 * Fornece os métodos em comum entre todas instruções SQL (SELECT, INSERT, DELETE e UPDATE)
 * @author Pablo Dall'Oglio
 */
abstract class SqlInstruction
{
    protected $sql;		     // armazena a instrução SQL
    protected $criteria;	 // armazena o objeto critério
    protected $entity;
    
    /**
     * Define o nome da entidade (tabela) manipulada pela instrução SQL
     * @param $entity = tabela
     */
    final public function setEntity($entity)
    {
        $this->entity = $entity;
    }
    
    /**
     * Retorna o nome da entidade (tabela)
     */
    final public function getEntity()
    {
        return $this->entity;
    }
    
    /**
     * Define um critério de seleção dos dados
     * @param $criteria = objeto do tipo Criteria
     */
    public function setCriteria(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }
    
    /**
     * Obrigamos sua declaração nas classes filhas,
     *     uma vez que seu comportamento será distinto em cada uma delas
     */
    abstract function getInstruction();
}
