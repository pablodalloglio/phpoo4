<?php
namespace Livro\Database;

/**
 * Classe abstrata para permitir definição de expressões
 * @author Pablo Dall'Oglio
 */
abstract class Expression
{
    // operadores lógicos
    const AND_OPERATOR = 'AND ';
    const OR_OPERATOR = 'OR ';
    
    // marca método dump como obrigatório
    abstract public function dump();
}
