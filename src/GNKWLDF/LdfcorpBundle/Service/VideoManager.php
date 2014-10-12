<?php
namespace GNKWLDF\LdfcorpBundle\Service;

use Gnuk\Video\Validator\VideoChecker;

class VideoManager {

    private $videoChecker;
    
    public function __construct()
    {
        $this->videoChecker = VideoChecker::getInstance();
        $this->videoChecker->addChecker("Gnuk\\Extra\\Video\\Validator\\YoutubeChecker");
        $this->videoChecker->addChecker("Gnuk\\Extra\\Video\\Validator\\DailymotionChecker");
    }

    public function getChecker($url)
    {
        return $this->videoChecker->getChecker($url);
    }
}
