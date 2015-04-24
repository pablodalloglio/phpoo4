<?php
namespace Livro\Database;

/**
 * Permite definição de critérios
 * @author Pablo Dall'Oglio
 */
class Criteria extends Expression
{
    private $expressions; // armazena a lista de expressões
    private $operators;     // armazena a lista de operadores
    private $properties;    // propriedades do critério
    
    /**
     * Método Construtor
     */
    function __construct()
    {
        $this->expressions = array();
        $this->operators = array();
    }
    
    /**
     * Adiciona uma expressão ao critério
     * @param $expression = expressão (objeto Expression)
     * @param $operator   = operador lógico de comparação
     */
    public function add(Expression $expression, $operator = self::AND_OPERATOR)
    {
        // na primeira vez, não precisamos de operador lógico para concatenar
        if (empty($this->expressions))
        {
            $operator = NULL;
        }
        
        // agrega o resultado da expressão à lista de expressões
        $this->expressions[] = $expression;
        $this->operators[]    = $operator;
    }
    
    /**
     * Retorna a expressão final
     */
    public function dump()
    {
        // concatena a lista de expressões
        if (is_array($this->expressions))
        {
            if (count($this->expressions) > 0)
            {
                $result = '';
                foreach ($this->expressions as $i=> $expression)
                {
                    $operator = $this->operators[$i];
                    // concatena o operador com a respectiva expressão
                    $result .= $operator. $expression->dump() . ' ';
                }
                $result = trim($result);
                return "({$result})";
            }
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
