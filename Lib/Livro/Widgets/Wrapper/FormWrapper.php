<?php
namespace Livro\Widgets\Wrapper;

use Livro\Widgets\Form\Form;
use Livro\Widgets\Base\Element;

/**
 * Decora formulários no formato Bootstrap
 */
class FormWrapper
{
    private $decorated;
    
    /**
     * Constrói o decorator
     */
    public function __construct(Form $form)
    {
        $this->decorated = $form;
    }
    
    /**
     * Redireciona chamadas para o objeto decorado
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->decorated, $method),$parameters);
    }
    
    /**
     * Exibe o formulário
     */
    public function show()
    {
        $element = new Element('form');
        $element->class = "form-horizontal";
        $element->enctype = "multipart/form-data";
        $element->method  = 'post';    // método de transferência
        $element->name  = $this->decorated->getName();
        
        foreach ($this->decorated->getFields() as $field)
        {
            $group = new Element('div');
            $group->class = 'form-group';
            
            $label = new Element('label');
            
            $label->class= 'col-sm-2 control-label';
            $label->add($field->getLabel());
            $group->add($label);
            $col = new Element('div');
            $col->class = 'col-sm-10';
            $col->add($field);
            
            $field->class = 'form-control';
            
            $group->add($col);
            $element->add($group);
        }
        
        $group = new Element('div');
        $group->class = 'form-group';
        
        $col = new Element('div');
        $col->class = 'col-sm-offset-2 col-sm-10"';
        
        $i = 0;
        foreach ($this->decorated->getActions() as $action)
        {
            $col->add($action);
            $class = ($i==0) ? 'btn-success' : 'btn-default';
            $action->class = 'btn '.$class;
            $i ++;
        }
        
        $group->add($col);
        $element->add($group);
        
        $element->width = '100%';
        $element->show();
    }
}
