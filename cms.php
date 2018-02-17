<?php

use fm\FM, fm\lib\help\ClassLoader;

define('CMS_ROOT', realpath(dirname(__FILE__)) . '/');

if(!defined('APP_CORE'))
    define('APP_CORE', str_replace(basename(__DIR__) . '/', '', CMS_ROOT));

require_once (APP_CORE . 'fm/fm.php');
FM::includer(CMS_ROOT . 'resources/constants.php');
FM::includer(CMS_RESOURCES . 'registry.php');

class CMS
{
    /**
     * @var Fdb
     */
    public static $db;

    public static $view;

    public static $db_prefix;

    private static $site_domain;

    /**
     * Globalno podesavanje iz admina
     * @var - array
     */
    private static $glob_config;

    private static $admin_theme = CMS_THEME;

    public static function set_db($strDbPathConfig)
    {
        self::$db = ClassLoader::load('Fdb');
        self::$db->connect(FM::includer($strDbPathConfig, false));
    }

    public static function set_view()
    {
        self::$view = ClassLoader::load("Cview");
        self::$view->set_theme(APP_THEME);
        self::$view->set_type(Ffetch::name('view'));
        self::$view->set_cache(APP_CACHE_SMARTY_CACHE);
        self::$view->set_theme_cache(APP_CACHE_SMARTY_COMPILE);
        self::$view->load_smarty();
    }

    public static function set_admin_theme($strThemePath)
    {
        self::$admin_theme = $strThemePath;
    }

    public static function set_site_domain()
    {
        self::$site_domain = FM::get_server_protocol() . FM::get_site_domain();
    }

    public static function get_site_domain()
    {
        return self::$site_domain;
    }

    public static function set_global_config()
    {
        $strSql = "SELECT c.name, c.value AS main_value, m.value AS mlc_value
                    FROM app_config c
                    LEFT JOIN app_config_mlc m ON (m.sid = c.id AND m.lang = '" . Clang::get_current() . "')";

        CMS::$db->query($strSql);
        if(CMS::$db->row_count() > 0)
        {
            $arrData = CMS::$db->fetch();

            foreach($arrData as $val)
            {
                $arrData[$val['name']] = $val['main_value'];

                if(FM::is_variable($val['mlc_value']))
                    $arrData[$val['name']] = $val['mlc_value'];
            }

            self::$glob_config = $arrData;
        }
    }

    public static function get_global_config()
    {
        return self::$glob_config;
    }

    public static function get_admin_theme()
    {
        return self::$admin_theme;
    }
}