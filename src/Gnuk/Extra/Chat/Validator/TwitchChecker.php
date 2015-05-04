<?php

namespace Gnuk\Extra\Chat\Validator;


use Gnuk\Iframe\Validator\BaseChecker;

class TwitchChecker extends BaseChecker {
    const TYPE = "Twitch";

    private $code;

    public function getType()
    {
        return self::TYPE;
    }

    public function isValidSyntax()
    {
        $matches = array();
        $options = $this->getUrlOptions();
        if(preg_match('#^(https?\:\/\/)?(www\.)?twitch\.tv\/widgets\/live\_embed\_player\.swf#', $this->getUrl()) && isset($options["channel"]))
        {
            $code = $options["channel"];
            if(preg_match('#^[a-zA-Z0-9\-\_]+$#', $code))
            {
                $this->code = $code;
                return true;
            }
        }
        if(preg_match('#^(https?\:\/\/)?(www\.)?twitch\.tv\/([a-zA-Z0-9\-\_]+)#', $this->getUrl(), $matches) AND !empty($matches[3]))
        {
            if(!in_array($matches[3], array(
                "widgets",
                "directory",
                "messages",
                "subscriptions"
            ), true)) {
                $this->code = $matches[3];
                return true;
            }
        }
        return false;
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