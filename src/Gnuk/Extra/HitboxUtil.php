<?php

namespace Gnuk\Extra;


class HitboxUtil {

    const TYPE = "Hitbox";

    /**
     * @param $url
     * @return string|null code
     */
    public static function getCodeFromUrl($url) {
        $matches = array();
        if(preg_match('#^((https?\:)?\/\/)?(www\.)?hitbox\.tv\/embedchat\/([a-zA-Z0-9]+)#', $url, $matches) AND !empty($matches[4]))
        {
            return $matches[4];
        }
        if(preg_match('#^((https?\:)?\/\/)?(www\.)?hitbox\.tv\/embed\/([a-zA-Z0-9]+)#', $url, $matches) AND !empty($matches[4]))
        {
            return $matches[4];
        }
        if(preg_match('#^((https?\:)?\/\/)?(www\.)?hitbox\.tv\/([a-zA-Z0-9]+)#', $url, $matches) AND !empty($matches[4]))
        {
            return $matches[4];
        }
        return null;
    }
}