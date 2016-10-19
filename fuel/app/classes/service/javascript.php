<?php

namespace Service;

class Javascript
{

    protected static $aJavascript = array();
    protected static $aJavascriptVars = array();

    /**
     * Add a javascript var
     * @param string $sName
     * @param mixed $mValue
     */
    public static function addVar($sName, $mValue)
    {
        self::$aJavascriptVars[$sName] = addslashes($mValue);
    }

    /**
     * Add a javascript json var
     * @param string $sName
     * @param string $mValue
     */
    public static function addJsonVar($sName, $mValue)
    {
        self::$aJavascriptVars[$sName] = $mValue;
    }

    /**
     * Add a javascript call in the view rendering
     * @param string $sJsCode
     */
    public static function call($sJsCode)
    {
        self::$aJavascript[] = $sJsCode;
    }

    /**
     * Return javascript call array
     */
    public static function getCall()
    {
        return self::$aJavascript;
    }

    /**
     * Return javascript vars array
     */
    public static function getVars()
    {
        return self::$aJavascriptVars;
    }
}
