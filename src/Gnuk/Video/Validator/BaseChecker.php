<?php
namespace Gnuk\Video\Validator;

use Gnuk\Video\Validator\Tools\Parameter;
use Gnuk\Video\Validator\Tools\Parameters;

/**
 * BaseChecker class
 * @author Anthony
 * @since 02/10/2014
 */
abstract class BaseChecker implements Checker
{

    private $valid;

    private $url;
    
    private $parameters;
    
    private $urlOptions;
    
    /**
    * BaseChecker constructor
    */
    public function __construct($url)
    {
        $this->url = $url;
        $this->parameters = new Parameters();
        $this->treatUrlOptions();
        $this->initParameters();
        $this->parameters->trySetValues($this->urlOptions);
    }
    
    protected abstract function initParameters();
    protected abstract function isValidSyntax();
    protected abstract function processIframe();
    
    

    protected function addParameter($key, $value, $options)
    {
        $this->parameters[$key] = new Parameter($value, $options);
    }
    
    private function treatUrlOptions()
    {
        $this->urlOptions = array();
        $parse = parse_url($this->url);
        if(isset($parse["query"]))
        {
            $queries = $parse["query"];
            parse_str($queries, $this->urlOptions);
        }
        
    }
    
    public function isValid()
    {
        if(null == $this->valid) {
            $this->valid = $this->isValidSyntax();
        }
        return $this->valid;
    }
    
    public function getUrlOptions()
    {
        return $this->urlOptions;
    }
    
    public function getParameters()
    {
        return $this->parameters;
    }
    
    public function getParameter($key)
    {
        return $this->parameters[$key]->getValue();
    }
    
    public function setParameter($key, $value)
    {
        return $this->parameters[$key]->setValue($value);
    }
    
    public function getIframe()
    {
        if(!$this->isValidSyntax()) {
            throw new UrlCheckerException("Impossible to get iframe because of invalid base url");
        }
        return $this->processIframe();
    }
    
    public function getUrl()
    {
        return $this->url;
    }
}
