<?php
namespace Livro\Widgets\Base;

/**
 * Classe suporte para tags
 * @author Pablo Dall'Oglio
 */
class Element
{
    protected $tagname;       // nome da TAG
    protected $properties;    // propriedades da TAG
    protected $children;
    
    /**
     * Instancia uma tag
     * @param $name = nome da tag
     */
    public function __construct($name)
    {
        // define o nome do elemento
        $this->tagname = $name;
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
        return isset($this->properties[$name])? $this->properties[$name] : NULL;
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
        echo "<{$this->tagname}";
        if ($this->properties)
        {
            // percorre as propriedades
            foreach ($this->properties as $name=>$value)
            {
                if (is_scalar($value))
                {
                    echo " {$name}=\"{$value}\"";
                }
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
     * Converte elemento em string
     */
    public function __toString()
    {
        ob_start();
        $this->show();
        $content = ob_get_clean();
        
        return $content;
    }
    
    /**
     * Fecha uma tag HTML
     */
    private function close()
    {
        echo "</{$this->tagname}>\n";
    }
}
