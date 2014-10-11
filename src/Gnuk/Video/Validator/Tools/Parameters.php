<?php
namespace Gnuk\Video\Validator\Tools;

use Countable;
use IteratorAggregate;
use ArrayAccess;
use ArrayIterator;

class Parameters implements Countable, IteratorAggregate, ArrayAccess {

    private $parameters;
    
    public function __construct()
    {
        $this->parameters = array();
    }

    public function count()
    {
        return count($this->parameters);
    }
    
    public function getIterator()
    {
        return new ArrayIterator($this->parameters);
    }
    
    public function offsetSet($offset, $value)
    {
        if(!($value instanceof Parameter))
        {
            throw new ParameterException("Only parameters from Parameter class can be set");
        }
        if(!is_string($offset))
        {
            throw new ParameterException("The offset should be a string");
        }
        
        $this->parameters[$offset] = $value;
    }
    
    public function offsetExists($offset)
    {
        return isset($this->parameters[$offset]);
    }
    
    public function offsetUnset($offset)
    {
        unset($this->parameters[$offset]);
    }
    
    public function offsetGet($offset)
    {
        if(!isset($this->parameters[$offset]))
        {
            throw new ParameterException("No parameter for this offset");
        }
        return $this->parameters[$offset];
    }
    
    public function getValues()
    {
        $array = array();
        foreach($this->parameters AS $offset => $parameter) {
            $array[$offset] = $parameter->getValue();
        }
        return $array;
    }
    
    public function trySetValues($values)
    {
        foreach($values AS $key => $value) {
            if(isset($this->parameters[$key]))
            {
                try {
                    $this->parameters[$key]->setValue($value);
                }
                catch(ParameterException $e){}
            }
        }
    }
}