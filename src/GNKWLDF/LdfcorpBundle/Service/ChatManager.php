<?php

namespace GNKWLDF\LdfcorpBundle\Service;

use Gnuk\Iframe\Validator\IframeChecker;

class ChatManager {
    private $chatChecker;

    public function __construct()
    {
        $this->chatChecker = IframeChecker::getInstance();
        $this->chatChecker->addChecker("Gnuk\\Extra\\Chat\\Validator\\TwitchChecker");
    }

    /**
     * @param $url
     * @return \Gnuk\Iframe\Validator\IframeChecker
     */
    public function getChecker($url)
    {
        return $this->chatChecker->getChecker($url);
    }
}