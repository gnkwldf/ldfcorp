<?php
namespace Gnuk\Video\Validator;
class VideoChecker {
    private $checkers;
    
    private static $instance = null;
    
    private function __construct()
    {
        $this->checkers = array();
    }
    
    public function setCheckers($checkers)
    {
        $this->checkers = $checkers;
    }
    
    public static function getInstance()
    {
        if(null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
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
