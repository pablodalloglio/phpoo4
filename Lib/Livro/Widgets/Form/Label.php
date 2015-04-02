<?php
Namespace Livro\Widgets\Form;

/**
 * classe Label
 * classe para construção de rótulos de texto
 */
class Label extends Field implements WidgetInterface
{
    private $fontStyle;
    private $embedStyle;
    protected $size;
    protected $id;
    
    /**
     * Class Constructor
     * @param  $value text label
     */
    public function __construct($value)
    {
        $this->id = uniqid();
        $stylename = 'tlabel_'.$this->id;
        
        // set the label's content
        $this->setValue($value);
        
        $this->embedStyle = new TStyle($stylename);
        
        // create a new element
        $this->tag = new TElement('label');
    }
    
    /**
     * Clone the object
     */
    public function __clone()
    {
        parent::__clone();
        $this->embedStyle = clone $this->embedStyle;
    }
    
    /**
     * Define the font size
     * @param $size Font size in pixels
     */
    public function setFontSize($size)
    {
        $this->embedStyle-> font_size    = $size.'pt';
    }
    
    /**
     * Define the style
     * @param $style string "b,i,u"
     */
    public function setFontStyle($style)
    {
        $this->fontStyle = $style;
    }
    
    /**
     * Define the font face
     * @param $font Font Family Name
     */
    public function setFontFace($font)
    {
        $this->embedStyle-> font_family = $font;
    }
    
    /**
     * Define the font color
     * @param $color Font Color
     */
    public function setFontColor($color)
    {
        $this->embedStyle-> color = $color;
    }
    
    /**
     * Add an object inside the label
     * @param $obj An Object
     */
    function add($obj)
    {
        $this->tag->add($obj);
    }
    
    /**
     * Shows the widget at the screen
     */
    public function show()
    {
        if ($this->size)
        {
            $this->embedStyle-> width = $this->size.'px';
        }
        
        // if the embed style has any content
        if ($this->embedStyle->hasContent())
        {
            $this->setProperty('style', $this->embedStyle->getInline(), TRUE);
        }
        
        if ($this->fontStyle)
        {
            $pieces = explode(',', $this->fontStyle);
            if ($pieces)
            {
                $value = $this->value;
                foreach ($pieces as $piece)
                {
                    $value = "<{$piece}>$value</{$piece}>";
                }
            }
            // add content to the tag
            $this->tag->add($value);
        }
        else
        {
            // add content to the tag
            $this->tag->add($this->value);
        }
        
        // show the tag
        $this->tag->show();
    }
}