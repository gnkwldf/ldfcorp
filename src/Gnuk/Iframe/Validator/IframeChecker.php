<?php

namespace Gnuk\Iframe\Validator;

class IframeChecker {
    private $checkers;
    
    public function __construct()
    {
        $this->checkers = array();
    }
    
    public function setCheckers($checkers)
    {
        $this->checkers = $checkers;
    }
    
    public function addChecker($checker)
    {
        $this->checkers[] = $checker;
    }
    
    public function getChecker($url)
    {
        foreach($this->checkers AS $checkerName) {
            $checker = new $checkerName($url);
            if($checker instanceof Checker){
                if($checker->isValid())
                {
                    return $checker;
                }
            }
        }
        return null;
    }
}
