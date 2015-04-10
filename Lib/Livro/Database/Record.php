<?php
Namespace Livro\Database;

use Exception;

/**
 * Permite definir um Active Record
 * @author Pablo Dall'Oglio
 */
abstract class Record implements RecordInterface
{
    protected $data; // array contendo os dados do objeto
    
    /**
     * Instancia um Active Record. Se passado o $id, já carrega o objeto
     * @param [$id] = ID do objeto
     */
    public function __construct($id = NULL)
    {
        if ($id) // se o ID for informado
        {
            // carrega o objeto correspondente
            $object = $this->load($id);
            if ($object)
            {
                $this->fromArray($object->toArray());
            }
        }
    }
    
    /**
     * Limpa o ID para que seja gerado um novo ID para o clone.
     */
    public function __clone()
    {
        unset($this->id);
    }
    
    /**
     * Executado sempre que uma propriedade for atribuída.
     */
    public function __set($prop, $value)
    {
        // verifica se existe método set_<propriedade>
        if (method_exists($this, 'set_'.$prop))
        {
            // executa o método set_<propriedade>
            call_user_func(array($this, 'set_'.$prop), $value);
        }
        else
        {
            if ($value === NULL)
            {
                unset($this->data[$prop]);
            }
            else
            {
                // atribui o valor da propriedade
                $this->data[$prop] = $value;
            }
        }
    }
    
    /**
     * Executado sempre que uma propriedade for requerida
     */
    public function __get($prop)
    {
        // verifica se existe método get_<propriedade>
        if (method_exists($this, 'get_'.$prop))
        {
            // executa o método get_<propriedade>
            return call_user_func(array($this, 'get_'.$prop));
        }
        else
        {
            // retorna o valor da propriedade
            if (isset($this->data[$prop]))
            {
                return $this->data[$prop];
            }
        }
    }
    
    /**
     * Retorna o nome da entidade (tabela)
     */
    private function getEntity()
    {
        // obtém o nome da classe
        $class = get_class($this);
        
        // retorna a constante de classe TABLENAME
        return constant("{$class}::TABLENAME");
    }
    
    /**
     * Preenche os dados do objeto com um array
     */
    public function fromArray($data)
    {
        $this->data = $data;
    }
    
    /**
     * Retorna os dados do objeto como array
     */
    public function toArray()
    {
        return $this->data;
    }
    
    /**
     * Armazena o objeto na base de dados
     */
    public function store()
    {
        // verifica se tem ID ou se existe na base de dados
        if (empty($this->data['id']) or (!$this->load($this->id)))
        {
            // incrementa o ID
            if (empty($this->data['id']))
            {
                $this->id = $this->getLast() +1;
            }
            // cria uma instrução de insert
            $sql = new SqlInsert;
            $sql->setEntity($this->getEntity());
            // percorre os dados do objeto
            foreach ($this->data as $key => $value)
            {
                // passa os dados do objeto para o SQL
                $sql->setRowData($key, $this->$key);
            }
        }
        else
        {
            // instancia instrução de update
            $sql = new SqlUpdate;
            $sql->setEntity($this->getEntity());
            // cria um critério de seleção baseado no ID
            $criteria = new Criteria;
            $criteria->add(new Filter('id', '=', $this->id));
            $sql->setCriteria($criteria);
            // percorre os dados do objeto
            foreach ($this->data as $key => $value)
            {
                if ($key !== 'id') // o ID não precisa ir no UPDATE
                
                {
                    // passa os dados do objeto para o SQL
                    $sql->setRowData($key, $this->$key);
                }
                
            }
        }
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // faz o log e executa o SQL
            Transaction::log($sql->getInstruction());
            $result = $conn->exec($sql->getInstruction());
            // retorna o resultado
            return $result;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
    
    /*
     * Recupera (retorna) um objeto da base de dados pelo seu ID
     * @param $id = ID do objeto
     */
    public function load($id)
    {
        // instancia instrução de SELECT
        $sql = new SqlSelect;
        $sql->setEntity($this->getEntity());
        $sql->addColumn('*');
        
        // cria critério de seleção baseado no ID
        $criteria = new Criteria;
        $criteria->add(new Filter('id', '=', $id));
        
        // define o critério de seleção de dados
        $sql->setCriteria($criteria);
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // cria mensagem de log e executa a consulta
            Transaction::log($sql->getInstruction());
            $result= $conn->Query($sql->getInstruction());
            
            // se retornou algum dado
            if ($result)
            {
                // retorna os dados em forma de objeto
                $object = $result->fetchObject(get_class($this));
            }
            return $object;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
    
    /**
     * Exclui um objeto da base de dados através de seu ID.
     * @param $id = ID do objeto
     */
    public function delete($id = NULL)
    {
        // o ID é o parâmetro ou a propriedade ID
        $id = $id ? $id : $this->id;
        
        // instancia uma instrução de DELETE
        $sql = new SqlDelete;
        $sql->setEntity($this->getEntity());
        
        // cria critério de seleção de dados
        $criteria = new Criteria;
        $criteria->add(new Filter('id', '=', $id));
        
        // define o critério de seleção baseado no ID
        $sql->setCriteria($criteria);
        
        // obtém transação ativa
        if ($conn = Transaction::get())
        {
            // faz o log e executa o SQL
            Transaction::log($sql->getInstruction());
            $result = $conn->exec($sql->getInstruction());
            // retorna o resultado
            return $result;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
    
    /**
     * Retorna o último ID
     */
    private function getLast()
    {
        // inicia transação
        if ($conn = Transaction::get())
        {
            // instancia instrução de SELECT
            $sql = new SqlSelect;
            $sql->addColumn('max(ID) as ID');
            $sql->setEntity($this->getEntity());
            
            // cria log e executa instrução SQL
            Transaction::log($sql->getInstruction());
            $result= $conn->Query($sql->getInstruction());
            
            // retorna os dados do banco
            $row = $result->fetch();
            return $row[0];
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa!!');
        }
    }
}
