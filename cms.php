<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 2:16 PM
 */

namespace cms;

use cms\lib\help\Lang;
use cms\lib\publisher\View;
use fm\FM, fm\lib\help\ClassLoader;
use fm\lib\help\Request;
use fm\lib\publisher\DatabaseEngine;

define('CMS_ROOT', realpath(dirname(__FILE__)) . '/');

if(!defined('APP_CORE'))
    define('APP_CORE', str_replace(basename(__DIR__) . '/', '', CMS_ROOT));

require_once (APP_CORE . 'fm/fm.php');
FM::includer(CMS_ROOT . 'resources/constants.php');
FM::includer(CMS_RESOURCES . 'registry.php');

class CMS
{
    /**
     * @var DatabaseEngine
     */
    public static $db;

    /**
     * @var View
     */
    public static $view;

    public static $viewFormat = FM_HTML;

    public static $dbPrefix;

    public static $userPermission = CMS_GUEST;

    public static $user;

    protected static $siteDomain;

    /**
     * @var array - Global settings from admin
     */
    protected static $globalConfig;

    protected static $adminTheme = CMS_THEME;

    public static function setDatabase($strDbPathConfig)
    {
        self::$db = ClassLoader::load('fm\lib\publisher\DatabaseEngine');
        self::$db->connect(FM::includer($strDbPathConfig, false));
    }

    public static function setView()
    {
        self::$view = ClassLoader::load('cms\lib\publisher\View');
        self::$view->setTheme(APP_THEME);
        self::$view->setType(Request::name('view'));
        self::$view->setCache(APP_CACHE_SMARTY_CACHE);
        self::$view->setThemeCache(APP_CACHE_SMARTY_COMPILE);
        self::$view->loadSmarty();
    }

    public static function setAdminTheme($strThemePath)
    {
        self::$adminTheme = $strThemePath;
    }

    public static function setSiteDomain()
    {
        self::$siteDomain = FM::getServerProtocol() . FM::getSiteDomain();
    }

    public static function getSiteDomain()
    {
        return self::$siteDomain;
    }

    public static function setGlobalConfig()
    {
        $strSql = "SELECT c.name, c.value AS main_value, m.value AS mlc_value
                    FROM app_config c
                    LEFT JOIN app_config_mlc m ON (m.sid = c.id AND m.lang = '" . Lang::getCurrent() . "')";

        CMS::$db->query($strSql);
        if(CMS::$db->rowCount() > 0)
        {
            $arrData = CMS::$db->fetch();

            foreach($arrData as $val)
            {
                $arrData[$val['name']] = $val['main_value'];

                if(isset($val['mlc_value']))
                    $arrData[$val['name']] = $val['mlc_value'];
            }

            self::$globalConfig = $arrData;
        }
    }

    public static function getGlobalConfig()
    {
        return self::$globalConfig;
    }

    public static function getAdminTheme()
    {
        return self::$adminTheme;
    }
}