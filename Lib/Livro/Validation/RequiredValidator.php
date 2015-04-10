<?php
Namespace Livro\Validation;

/**
 * Validador de campo obrigatório
 * @author Pablo Dall'Oglio
 */
class RequiredValidator extends FieldValidator
{
    public function validate($label, $value, $parameters = NULL)
    {
        if (empty($value))
        {
            throw new Exception("O campo {$label} é obrigatório");
        }
    }
}
