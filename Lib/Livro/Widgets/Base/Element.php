<?php
Namespace Livro\Widgets\Base;

/**
 * Classe suporte para tags
 * @author Pablo Dall'Oglio
 */
class Element
{
    private $name;          // nome da TAG
    private $properties;    // propriedades da TAG
    protected $children;
    
    /**
     * Instancia uma tag
     * @param $name = nome da tag
     */
    public function __construct($name)
    {
        // define o nome do elemento
        $this->name = $name;
    }
    
    /**
     * Intercepta as atribuições à propriedades do objeto
     * @param $name   = nome da propriedade
     * @param $value  = valor
     */
    public function __set($name, $value)
    {
        // armazena os valores atribuídos ao array properties
        $this->properties[$name] = $value;
    }
    
    /**
     * Retorna a propriedade
     * @param $name   = nome da propriedade
     */
    public function __get($name)
    {
        // retorna o valores atribuídos ao array properties
        return $this->properties[$name];
    }
    
    /**
     * Adiciona um elemento filho
     * @param $child = objeto filho
     */
    public function add($child)
    {
        $this->children[] = $child;
    }
    
    /**
     * Exibe a tag de abertura na tela
     */
    private function open()
    {
        // exibe a tag de abertura
        echo "<{$this->name}";
        if ($this->properties)
        {
            // percorre as propriedades
            foreach ($this->properties as $name=>$value)
            {
                echo " {$name}=\"{$value}\"";
            }
        }
        echo '>';
    }
    
    /**
     * Exibe a tag na tela, juntamente com seu conteúdo
     */
    public function show()
    {
        // abre a tag
        $this->open();
        echo "\n";
        // se possui conteúdo
        if ($this->children)
        {
            // percorre todos objetos filhos
            foreach ($this->children as $child)
            {
                // se for objeto
                if (is_object($child))
                {
                    $child->show();
                }
                else if ((is_string($child)) or (is_numeric($child)))
                {
                    // se for texto
                    echo $child;
                }
            }
            // fecha a tag
            $this->close();
        }
    }
    
    /**
     * Fecha uma tag HTML
     */
    private function close()
    {
        echo "</{$this->name}>\n";
    }
}
