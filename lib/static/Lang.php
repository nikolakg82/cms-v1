<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 2:16 PM
 */

namespace cms\lib\help;

use fm\FM;

class Lang
{
    /**
     * @var string - Default lang
     */
    protected static $default = 'SR';

    /**
     * @var string - Current lang
     */
    protected static $current;

    /**
     * @var array - Labels
     */
    protected static $lab = array();

    /**
     * @var array - All lang
     *
     * Example: array('lang_key' => array(
     *                                  'name' => 'Lang name'
     *                                  )
     *          )
     */
    protected static $lang = array();

    public static function setDefault($strLangKey)
    {
        if(isset(self::$lang[$strLangKey]))
            self::$default = $strLangKey;
    }

    public static function setCurrent($strLangKey)
    {
        if(isset(self::$lang[$strLangKey]))
            self::$current = $strLangKey;
        else
            self::$current = self::getDefault();

        self::setLab(FM::includer(APP_LAB . self::getCurrent() . ".php"));
    }

    public static function setLab($arrLab)
    {
        self::$lab = $arrLab;
    }

    public static function getDefault()
    {
        return self::$default;
    }

    public static function getCurrent()
    {
        return self::$current;
    }

    public static function getLab()
    {
        return self::$lab;
    }

    public static function getLang($strKey = null)
    {
        $mixReturn = self::$lang;
        if(isset($strKey))
            $mixReturn = self::$lang[$strKey];

        return $mixReturn;
    }


    /**
     * Add lang to registry
     *
     * @param string $strLangKey - lang key
     * @param string $strLangName - Lang name
     */
    public static function addLang($strLangKey, $strLangName)
    {
        self::$lang[$strLangKey]['name'] = $strLangName;
    }
}