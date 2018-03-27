<?php
namespace Livro\Widgets\Wrapper;

use Livro\Widgets\Container\Panel;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Base\Element;

/**
 * Decora datagrids no formato Bootstrap
 */
class DatagridWrapper
{
    private $decorated;
    
    /**
     * Constrói o decorator
     */
    public function __construct(Datagrid $datagrid)
    {
        $this->decorated = $datagrid;
    }
    
    /**
     * Redireciona chamadas para o objeto decorado
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->decorated, $method), $parameters);
    }
    
    /**
     * Redireciona alterações em atributos
     */
    public function __set($attribute, $value)
    {
        $this->decorated->$attribute = $value;
    }
    
    /**
     * Exibe a datagrid
     */
    public function show()
    {
        $element = new Element('table');
        $element->class = 'table table-striped table-hover';
        
        // cria o header
        $thead = new Element('thead');
        $element->add($thead);
        $this->createHeaders($thead);
        
        // cria o body
        $tbody = new Element('tbody');
        $element->add($tbody);
        
        $items = $this->decorated->getItems();
        foreach ($items as $item)
        {
            $this->createItem($tbody, $item);
        }
        
        $panel = new Panel;
        $panel->type = 'datagrid';
        $panel->add($element);
        $panel->show();
    }
    
    /**
     * Cria a estrutura da Grid, com seu cabeçalho
     */
    public function createHeaders($thead)
    {
        // adiciona uma linha à tabela
        $row = new Element('tr');
        $thead->add($row);
        
        $actions = $this->decorated->getActions();
        $columns = $this->decorated->getColumns();
        
        // adiciona células para as ações
        if ($actions)
        {
            foreach ($actions as $action)
            {
                $celula = new Element('th');
                $celula->width = '40px';
                $row->add($celula);
            }
        }
        
        // adiciona as células para os títulos das colunas
        if ($columns)
        {
            // percorre as colunas da listagem
            foreach ($columns as $column)
            {
                // obtém as propriedades da coluna
                $label = $column->getLabel();
                $align = $column->getAlign();
                $width = $column->getWidth();
                
                $celula = new Element('th');
                $celula->add($label);
                $celula->style = "text-align:$align";
                $celula->width = $width;
                $row->add($celula);
                
                // verifica se a coluna tem uma ação
                if ($column->getAction())
                {
                    $url = $column->getAction();
                    $celula->onclick = "document.location='$url'";
                }
            }
        }
    }
    
    
    public function createItem($tbody, $item)
    {
        $row = new Element('tr');
        $tbody->add($row);
        
        $actions = $this->decorated->getActions();
        $columns = $this->decorated->getColumns();
        
        // verifica se a listagem possui ações
        if ($actions)
        {
            // percorre as ações
            foreach ($actions as $action)
            {
                // obtém as propriedades da ação
                $url   = $action['action']->serialize();
                $label = $action['label'];
                $image = $action['image'];
                $field = $action['field'];
                
                // obtém o campo do objeto que será passado adiante
                $key    = $item->$field;
                
                // cria um link
                $link = new Element('a');
                $link->href = "{$url}&key={$key}&{$field}={$key}";
                
                // verifica se o link será com imagem ou com texto
                if ($image)
                {
                    // adiciona a imagem ao link
                    $i = new Element('i');
                    $i->class = $image;
                    $i->title = $label;
                    $i->add('');
                    $link->add($i);
                }
                else
                {
                    // adiciona o rótulo de texto ao link
                    $link->add($label);
                }
                
                $element = new Element('td');
                $element->add($link);
                $element->align = 'center';
                
                // adiciona a célula à linha
                $row->add($element);
            }
        }
        
        if ($columns)
        {
            // percorre as colunas da Datagrid
            foreach ($columns as $column)
            {
                // obtém as propriedades da coluna
                $name     = $column->getName();
                $align    = $column->getAlign();
                $width    = $column->getWidth();
                $function = $column->getTransformer();
                $data     = $item->$name;
                
                // verifica se há função para transformar os dados
                if ($function)
                {
                    // aplica a função sobre os dados
                    $data = call_user_func($function, $data);
                }
                
                $element = new Element('td');
                $element->add($data);
                $element->align = $align;
                $element->width = $width;
                
                // adiciona a célula na linha
                $row->add($element);
            }
        }
    }
}
