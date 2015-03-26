<?php
namespace Gnuk\Extra\Video\Validator;

use Gnuk\Video\Validator\BaseChecker;

class YoutubeChecker extends BaseChecker
{
    const TYPE = "Youtube";
    
    private $code;
    
    public function getType()
    {
        return self::TYPE;
    }

    public function isValidSyntax()
    {
        $matches = array();
        $options = $this->getUrlOptions();
        
        if(preg_match('#^(https?\:\/\/)?(www\.)?youtube\.com\/watch#', $this->getUrl()) && isset($options["v"]))
        {
            $code = $options["v"];
            if(preg_match('#^[a-zA-Z0-9\-\_]+$#', $code))
            {
                $this->code = $code;
                return true;
            }
        }
        if(preg_match('#^((https?\:)?\/\/)?(www\.)?youtube\.com\/embed\/([a-zA-Z0-9\-\_]+)#', $this->getUrl(), $matches) AND !empty($matches[4]))
        {
            $this->code = $matches[4];
            return true;
        }
        if(preg_match('#^(https?\:\/\/)?(www\.)?youtu\.be\/([a-zA-Z0-9\-\_]+)#', $this->getUrl(), $matches) AND !empty($matches[3]))
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
        $url = "//www.youtube.com/embed/".$this->code;
        if("true" === $this->getParameter("autoplay"))
        {
            $url .= "?autoplay=true";
        }
        return $url;
    }
}
