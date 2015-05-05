<?php

namespace Gnuk\Extra\Chat\Validator;


use Gnuk\Extra\TwitchUtil;
use Gnuk\Iframe\Validator\BaseChecker;

class TwitchChecker extends BaseChecker {

    private $code;

    public function getType()
    {
        return TwitchUtil::TYPE;
    }

    public function isValidSyntax()
    {
        $code = TwitchUtil::getCodeFromUrl($this->getUrl(), $this->getUrlOptions());
        if(null === $code) {
            return false;
        }
        $this->code = $code;
        return true;
    }

    public function initParameters()
    {
        // No parameters
    }

    public function processIframe()
    {
        $url = "http://www.twitch.tv/".$this->code."/chat";
        return $url;
    }
}