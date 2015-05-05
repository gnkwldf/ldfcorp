<?php

namespace GNKWLDF\LdfcorpBundle\Service;

use Gnuk\Iframe\Validator\IframeChecker;

class ChatManager {
    private $chatChecker;

    public function __construct()
    {
        $this->chatChecker = new IframeChecker();
        $this->chatChecker->addChecker("Gnuk\\Extra\\Chat\\Validator\\TwitchChecker");
        $this->chatChecker->addChecker("Gnuk\\Extra\\Chat\\Validator\\HitboxChecker");
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