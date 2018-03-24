<?php
namespace Livro\Database;

/**
 * Permite definição de critérios
 * @author Pablo Dall'Oglio
 */
class Criteria
{
    private $filters; // armazena a lista de filtros
    
    /**
     * Método Construtor
     */
    function __construct()
    {
        $this->filters = array();
    }
    
    /**
     * Adiciona uma expressão ao critério
     * @param $variable           Variável/campo
     * @param $compare_operator   Operador de comparação
     * @param $value              Valor a ser comparado
     * @param $logic_operator     Operador lógico
     */
    public function add($variable, $compare_operator, $value, $logic_operator = 'and')
    {
        // na primeira vez, não precisamos concatenar
        if (empty($this->filters))
        {
            $logic_operator = NULL;
        }
        
        $this->filters[] = [$variable, $compare_operator, $this->transform($value), $logic_operator];
    }
    
    /**
     * Recebe um valor e faz as modificações necessárias
     *   para ele ser interpretado pelo banco de dados
     * @param $value = valor a ser transformado
     */
    private function transform($value)
    {
        // caso seja um array
        if (is_array($value))
        {
            // percorre os valores
            foreach ($value as $x)
            {
                // se for um inteiro
                if (is_integer($x))
                {
                    $foo[]= $x;
                }
                else if (is_string($x))
                {
                    // se for string, adiciona aspas
                    $foo[]= "'$x'";
                }
            }
            // converte o array em string separada por ","
            $result = '(' . implode(',', $foo) . ')';
        }
        // caso seja uma string
        else if (is_string($value))
        {
            // adiciona aspas
            $result = "'$value'";
        }
        // caso seja valor nullo
        else if (is_null($value))
        {
            // armazena NULL
            $result = 'NULL';
        }
        
        // caso seja booleano
        else if (is_bool($value))
        {
            // armazena TRUE ou FALSE
            $result = $value ? 'TRUE' : 'FALSE';
        }
        else
        {
            $result = $value;
        }
        // retorna o valor
        return $result;
    }
    
    /**
     * Retorna a expressão final
     */
    public function dump()
    {
        // concatena a lista de expressões
        if (is_array($this->filters) and count($this->filters) > 0)
        {
            $result = '';
            foreach ($this->filters as $filter)
            {
                $result .= $filter[3] . ' ' . $filter[0] . ' ' . $filter[1] . ' '. $filter[2] . ' ';
            }
            $result = trim($result);
            return "({$result})";
        }
    }
    
    /**
     * Define o valor de uma propriedade
     * @param $property = propriedade
     * @param $value    = valor
     */
    public function setProperty($property, $value)
    {
        if (isset($value))
        {
            $this->properties[$property] = $value;
        }
        else
        {
            $this->properties[$property] = NULL;
        }
    }
    
    /**
     * Retorna o valor de uma propriedade
     * @param $property = propriedade
     */
    public function getProperty($property)
    {
        if (isset($this->properties[$property]))
        {
            return $this->properties[$property];
        }
    }
}
