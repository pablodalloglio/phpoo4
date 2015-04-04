<?php
class EmailValidator extends FieldValidator
{
    public function validate($label, $value, $parameters = NULL)
    {
        if (!preg_match("/^(\w+((-\w+)|(\w.\w+))*)\@(\w+((\.|-)\w+)*\.\w+$)/",$value))
        {
            throw new Exception("O campo {$label} contém um email inválido");
        }
    }
}
?>