<?php
/**
 * Classe abstrata para validaчуo
 */
abstract class FieldValidator
{
    /**
     * Valida um valor
     * @param $label Nome do campo
     * @param $value Valor a ser validato
     * @param $parameters Parтmetros adicionais de validaчуo
     */
    abstract public function validate($label, $value, $parameters = NULL);
}
?>