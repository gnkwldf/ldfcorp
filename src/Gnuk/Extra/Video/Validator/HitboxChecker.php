<?php
namespace Gnuk\Extra\Video\Validator;

use Gnuk\Extra\HitboxUtil;
use Gnuk\Iframe\Validator\BaseChecker;

class HitboxChecker extends BaseChecker
{
    private $code;
    
    public function getType()
    {
        return HitboxUtil::TYPE;
    }

    public function isValidSyntax()
    {
        $code = HitboxUtil::getCodeFromUrl($this->getUrl());
        if(null === $code) {
            return false;
        }
        $this->code = $code;
        return true;
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