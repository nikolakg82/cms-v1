<?php

/**
 * Created by PhpStorm.
 * User: Nikola
 * Date: 5/3/2016
 * Time: 2:16 PM
 */

namespace cms\lib\help;

use fm\FM;

class Lang
{
    /**
     * @var string - Default jezik
     */
    protected static $default = 'SR';

    /**
     * @var string - Trenutni jezik na sajtu
     */
    protected static $current;

    /**
     * @var array - Labele
     */
    protected static $lab = array();

    /**
     * Niz sa aktivnim jezicima
     *
     * @var array('Kljuc jezika' => array(
     *                                  'name' => 'Naziv jezika'
     *                                  ''
     *                                  )
     *          )
     */
    protected static $lang = array();

    //Seteri
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


    //Geteri
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
     * Dodavanje jezika
     * @param string $strLangKey - Kljuc jezika
     * @param string $strLangName - Naziv jezika
     */
    public static function addLang($strLangKey, $strLangName)
    {
        self::$lang[$strLangKey]['name'] = $strLangName;
    }
}