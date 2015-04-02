<?php
Namespace Livro\Database;

/*
 * classe Expression
 * classe abstrata para gerenciar expressões
 */
abstract class Expression
{
    // operadores lógicos
    const AND_OPERATOR = 'AND ';
    const OR_OPERATOR = 'OR ';
    
    // marca método dump como obrigatório
    abstract public function dump();
}
