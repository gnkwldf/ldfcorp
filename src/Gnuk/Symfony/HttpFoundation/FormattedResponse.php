<?php
/*
* Copyright (c) 2013 GNKW
*
* This file is part of GNKW Symfony.
*
* GNKW Symfony is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* GNKW Symfony is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with GNKW Symfony.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Gnuk\Symfony\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

/**
 * This class let you use easily serializable formats in symfony
 * @author Anthony Rey
 * @since 04/12/2013
 */
class FormattedResponse extends Response
{
    /**
     * Custom data used if there is a specific case
     * @var mixed
     */
    private $customData;

    /**
     * Create the response
     * @param mixed $data Data to serialize
     * @param integer $status Response status
     * @param string|array $format Response format (json, jsonp …) or headers if the format is custom
     * @param mixed $customData Data to add (for specific format)
     */
    public function __construct($data, $status = 200, $format = 'json', $customData = null)
    {
        $this->customData = $customData;
        if('string' === gettype($format))
        {
            $format = trim(strtolower($format));
        }
        $content = $this->contentFromData($data, $format);
        $headers = $this->headersFromFormat($format);
        parent::__construct($content, $status, $headers);
    }

    /**
     * Generate headers from format
     * @param string|array Format or headers to use
     * @return array Headers
     */
    private function headersFromFormat($format)
    {
        $type = gettype($format);
        $headers = array();
        if($type === 'array')
        {
            $headers = $format;
        }
        else if($type === 'string')
        {
            switch ($format)
            {
                case 'json':
                    $contentType = 'application/json';
                    break;
                case 'jsonp':
                    $contentType = 'application/javascript';
                    break;
                default:
                    $contentType = 'text/plain';
                    break;
            }
            $headers['Content-Type'] = $contentType;
        }
        return $headers;
    }

    /**
     * Serialize content from data
     * @param mixed $data Data to serialize
     * @param string|array $format Serialize to this format
     * @return string Data serialized
     */
    private function contentFromData($data, $format)
    {
        $type = gettype($data);
        if($type === 'string')
        {
            $content = $data;
        }
        else if($type === 'array')
        {
            switch ($format)
            {
                case 'json':
                case 'jsonp':
                    $content = json_encode($data);
                    break;
                default:
                    $content = serialize($data);
                    break;
            }
            if($format === 'jsonp')
            {
                $customData = ('string' === gettype($this->customData)) ? $this->customData : 'data';
                if(!empty($_GET))
                {
                    $getKeys = array_keys($_GET);
                    $customData = $getKeys[0];
                }
                $customData = trim($customData);
                $content = $customData . '(' . $content . ');';
            }
        }
        else
        {
            $content = serialize($data);
        }
        return $content;
    }
}