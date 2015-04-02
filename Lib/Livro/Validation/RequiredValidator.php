<?php
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
?>