<?php
namespace Gnuk\Extra\Video\Validator;

use Gnuk\Video\Validator\BaseChecker;

class DailymotionChecker extends BaseChecker
{
    const TYPE = "Dailymotion";
    
    private $code;
    
    public function getType()
    {
        return self::TYPE;
    }

    public function isValidSyntax()
    {
        $matches = array();
        if(preg_match('#^((https?\:)?\/\/)?(www\.)?dailymotion\.com\/embed\/video\/([a-zA-Z0-9]+)#', $this->getUrl(), $matches) AND !empty($matches[4]))
        {
            $this->code = $matches[4];
            return true;
        }
        if(preg_match('#^(https?\:\/\/)?(www\.)?dailymotion\.com\/video\/([a-zA-Z0-9]+)#', $this->getUrl(), $matches) AND !empty($matches[3]))
        {
            $this->code = $matches[3];
            return true;
        }
        if(preg_match('#^(https?\:\/\/)?(www\.)?dai\.ly\/([a-zA-Z0-9]+)#', $this->getUrl(), $matches) AND !empty($matches[3]))
        {
            $this->code = $matches[3];
            return true;
        }
        return false;
    }
    
    public function initParameters()
    {
        $this->addParameter("autoplay", "false", array(
            "true", "false"
        ));
    }
    
    public function processIframe()
    {
        $url = "//www.dailymotion.com/embed/video/".$this->code;
        if("true" === $this->getParameter("autoplay"))
        {
            $url .= "?autoplay=true";
        }
        return $url;
    }
}