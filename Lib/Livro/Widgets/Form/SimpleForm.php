<?php
namespace Livro\Widgets\Form;

class SimpleForm
{
    private $name, $action, $fields, $title;
    
    public function __construct($name)
    {
        $this->name = $name;
        $this->fields = array();
        $this->title = '';
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function addField($label, $name, $type, $value, $class = '')
    {
        $this->fields[] = array( 'label' => $label, 'name'  => $name, 'type' => $type,
                                 'value' => $value, 'class' => $class);
    }
    
    public function setAction($action)
    {
        $this->action = $action;
    }
    
    public function show()
    {
        echo "<div class='panel panel-default' style='margin: 40px;'>\n";
        echo "<div class='panel-heading'> {$this->title} </div>\n";
        echo "<div class='panel-body'>\n";
        echo "<form method='POST' action='{$this->action}' class='form-horizontal'>\n";
        if ($this->fields) {
            foreach ($this->fields as $field) {
                echo "<div class='form-group'>\n";
                echo "<label class='col-sm-2 control-label'> {$field['label']} </label>\n";
                echo "<div class='col-sm-10'>\n";
                echo "<input type='{$field['type']}' name='{$field['name']}'
                             value='{$field['value']}' class='{$field['class']}'>\n";
                echo "</div>\n";
                echo "</div>\n";
            }
            echo "<div class='form-group'>\n";
            echo "<div class='col-sm-offset-2 col-sm-8'>\n";
            echo "<input type='submit' class='btn btn-success' value='enviar'>\n";
            echo "</div>\n";
            echo "</div>\n";
        }
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }
}
