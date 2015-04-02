<?php
class MinLengthValidator extends FieldValidator
{
    public function validate($label, $value, $parameters = NULL)
    {
        $tam = $parameters[0];
        
        if (strlen($value) < $tam)
        {
            throw new Exception("O campo {$label} não pode ter menos de {$tam}");
        }
    }
}
?>