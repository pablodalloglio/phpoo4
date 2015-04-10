<?php
Namespace Livro\Database;

/**
 * Representa uma instrução de DELETE no banco de dados
 * @author Pablo Dall'Oglio
 */
final class SqlDelete extends SqlInstruction
{
    /*
     * método getInstruction()
     * retorna a instrução de DELETE em forma de string.
     */
    public function getInstruction()
    {
        // monta a string de DELETE
        $this->sql = "DELETE FROM {$this->entity}";
        
        // retorna a cláusula WHERE do objeto $this->criteria
        if ($this->criteria)
        {
            $expression = $this->criteria->dump();
            if ($expression)
            {
                $this->sql .= ' WHERE ' . $expression;
            }
        }
        return $this->sql;
    }
}
