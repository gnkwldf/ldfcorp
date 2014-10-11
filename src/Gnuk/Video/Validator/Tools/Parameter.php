<?php
namespace Gnuk\Video\Validator\Tools;

class Parameter {
    
    private $value;
    private $options = array();

    public function __construct($value, $options)
    {
        $this->value = $value;
        if(is_array($options)) {
            $this->options = $options;
        }
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function setValueByKey($key)
    {
        if(!isset($this->options[$key])) {
            throw new ParameterException("There is no option with this key");
        }
        $this->value = $this->options[$key];
    }
    
    public function setValue($value)
    {
        if(!in_array($value, $this->options))
        {
            throw new ParameterException("This value is not in the options");
        }
        $this->value = $value;
    }
}