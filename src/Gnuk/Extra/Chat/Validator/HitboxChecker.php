<?php
/**
 * Created by IntelliJ IDEA.
 * User: anthony
 * Date: 05/05/15
 * Time: 20:27
 */

namespace Gnuk\Extra\Chat\Validator;


use Gnuk\Extra\HitboxUtil;
use Gnuk\Iframe\Validator\BaseChecker;

class HitboxChecker extends BaseChecker{

    private $code;

    protected function initParameters()
    {
        // No parameters
    }

    protected function isValidSyntax()
    {
        $code = HitboxUtil::getCodeFromUrl($this->getUrl());
        if(null === $code) {
            return false;
        }
        $this->code = $code;
        return true;
    }

    protected function processIframe()
    {
        return "//www.hitbox.tv/embedchat/".$this->code;
    }

    public function getType()
    {
        return HitboxUtil::TYPE;
    }
}