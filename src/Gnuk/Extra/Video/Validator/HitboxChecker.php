<?php
namespace Gnuk\Extra\Video\Validator;

use Gnuk\Video\Validator\BaseChecker;

class HitboxChecker extends BaseChecker
{
    const TYPE = "Hitbox";
    
    private $code;
    
    public function getType()
    {
        return self::TYPE;
    }

    public function isValidSyntax()
    {
        $matches = array();
        if(preg_match('#^((https?\:)?\/\/)?(www\.)?hitbox\.tv\/embed\/([a-zA-Z0-9]+)#', $this->getUrl(), $matches) AND !empty($matches[4]))
        {
            $this->code = $matches[4];
            return true;
        }
        if(preg_match('#^((https?\:)?\/\/)?(www\.)?hitbox\.tv\/([a-zA-Z0-9]+)#', $this->getUrl(), $matches) AND !empty($matches[4]))
        {
            $this->code = $matches[4];
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
        $url = "//www.hitbox.tv/embed/".$this->code;
        if("true" === $this->getParameter("autoplay"))
        {
            $url .= "?autoplay=true";
        }
        return $url;
    }
}