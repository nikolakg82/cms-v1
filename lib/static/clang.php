<?php

/**
 * Created by PhpStorm.
 * User: Nikola
 * Date: 5/3/2016
 * Time: 2:16 PM
 */
class Clang
{
    /**
     * @var string - Default jezik
     */
    private static $default = 'SR';

    /**
     * @var string - Trenutni jezik na sajtu
     */
    private static $current;

    /**
     * @var array - Labele
     */
    private static $lab = array();

    /**
     * Niz sa aktivnim jezicima
     *
     * @var array('Kljuc jezika' => array(
     *                                  'name' => 'Naziv jezika'
     *                                  ''
     *                                  )
     *          )
     */
    private static $lang = array();

    //Seteri
    public static function set_default($strLangKey)
    {
        if(FM::is_variable(self::$lang[$strLangKey]))
            self::$default = $strLangKey;
    }

    public static function set_current($strLangKey)
    {
        if(FM::is_variable(self::$lang[$strLangKey]))
            self::$current = $strLangKey;
        else
            self::$current = self::get_default();

        self::set_lab(FM::includer(APP_LAB . self::get_current() . ".php"));
    }

    public static function set_lab($arrLab)
    {
        self::$lab = $arrLab;
    }


    //Geteri
    public static function get_default()
    {
        return self::$default;
    }

    public static function get_current()
    {
        return self::$current;
    }

    public static function get_lab()
    {
        return self::$lab;
    }

    public static function get_lang($strKey = null)
    {
        $mixReturn = self::$lang;
        if(FM::is_variable($strKey))
            $mixReturn = self::$lang[$strKey];

        return $mixReturn;
    }


    /**
     * Dodavanje jezika
     * @param string $strLangKey - Kljuc jezika
     * @param string $strLangName - Naziv jezika
     */
    public static function add_lang($strLangKey, $strLangName)
    {
        self::$lang[$strLangKey]['name'] = $strLangName;
    }
}