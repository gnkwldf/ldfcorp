<?php
namespace GNKWLDF\LdfcorpBundle\Service;

use Gnuk\Iframe\Validator\IframeChecker;

class VideoManager {

    private $videoChecker;
    
    public function __construct()
    {
        $this->videoChecker = new IframeChecker();
        $this->videoChecker->addChecker("Gnuk\\Extra\\Video\\Validator\\YoutubeChecker");
        $this->videoChecker->addChecker("Gnuk\\Extra\\Video\\Validator\\DailymotionChecker");
        $this->videoChecker->addChecker("Gnuk\\Extra\\Video\\Validator\\TwitchChecker");
        $this->videoChecker->addChecker("Gnuk\\Extra\\Video\\Validator\\HitboxChecker");
    }

    /**
     * @param $url
     * @return \Gnuk\Iframe\Validator\IframeChecker
     */
    public function getChecker($url)
    {
        return $this->videoChecker->getChecker($url);
    }
}
