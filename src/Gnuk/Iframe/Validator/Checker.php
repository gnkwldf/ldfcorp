<?php

namespace Gnuk\Iframe\Validator;

/**
 * Checker class
 * @author Anthony
 * @since 02/10/2014
 */
interface Checker
{
    public function getType();
    public function getIframe();
    public function isValid();
    public function getParameters();
    public function getParameter($key);
    public function setParameter($key, $value);
}
