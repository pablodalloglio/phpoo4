<?php
Namespace Livro\Validation;

/**
 * Classe abstrata para validação
 * @author Pablo Dall'Oglio
 */
abstract class FieldValidator
{
    /**
     * Valida um valor
     * @param $label Nome do campo
     * @param $value Valor a ser validato
     * @param $parameters Parâmetros adicionais de validação
     */
    abstract public function validate($label, $value, $parameters = NULL);
}
