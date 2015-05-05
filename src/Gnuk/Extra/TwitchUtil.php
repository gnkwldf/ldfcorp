<?php
/**
 * Created by IntelliJ IDEA.
 * User: anthony
 * Date: 05/05/15
 * Time: 20:38
 */

namespace Gnuk\Extra;


class TwitchUtil {

    const TYPE = "Twitch";

    /**
     * @param string $url
     * @param array $options
     * @return string|null code
     */
    public static function getCodeFromUrl($url, $options) {
        $matches = array();
        if(preg_match('#^(https?\:\/\/)?(www\.)?twitch\.tv\/widgets\/live\_embed\_player\.swf#', $url) && isset($options["channel"]))
        {
            $code = $options["channel"];
            if(preg_match('#^[a-zA-Z0-9\-\_]+$#', $code))
            {
                return  $code;
            }
        }
        if(preg_match('#^(https?\:\/\/)?(www\.)?twitch\.tv\/([a-zA-Z0-9\-\_]+)#', $url, $matches) AND !empty($matches[3]))
        {
            if(!in_array($matches[3], array(
                "widgets",
                "directory",
                "messages",
                "subscriptions"
            ), true)) {
                return $matches[3];
            }
        }
        return null;
    }
}