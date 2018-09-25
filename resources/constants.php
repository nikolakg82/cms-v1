<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 2:16 PM
 */

/**
 * Define CMS constants
 */

/**
 * Path to folders
 */
    define('CMS_LIB',               CMS_ROOT        . 'lib/');
    define('CMS_ABSTRACT',          CMS_LIB         . 'abstract/');
    define('CMS_PUBLIC',            CMS_LIB         . 'public/');
    define('CMS_STATIC',            CMS_LIB         . 'static/');
    define('CMS_INTERFACE',         CMS_LIB         . 'interface/');
    define('CMS_MVC',               CMS_LIB         . 'mvc/');
    define('CMS_CONTROLLER',        CMS_MVC         . 'controller/');
    define('CMS_MODEL',             CMS_MVC         . 'model/');
    define('CMS_VIEW',              CMS_MVC         . 'view/');

    define('CMS_RESOURCES',         CMS_ROOT        . 'resources/');

    define('CMS_CONFIG',            CMS_ROOT        . 'config/');

    define('CMS_SMARTY',            CMS_LIB        . 'smarty-3.1.29/libs/');

    define('CMS_THEME',             CMS_ROOT        . 'theme/');

/**
 * Main controllers key
 */
    define('CMS_C_STRUCTURE',       'structure');//Ovo nije realan controler, ne koristi se nigde, potreban je samo zbog putanja za templete
    define('CMS_C_ADMIN',           'admin');
    define('CMS_C_INDEX',           'index');
    define('CMS_C_PAGE',            'page');
    define('CMS_C_NEWS',            'news');
    define('CMS_C_USER',            'user');
    define('CMS_C_GALLERY',         'gallery');
    define('CMS_C_CONTACT',         'contact');

/**
 * Lang
 */
    define('CMS_SR',                'SR');
    define('CMS_EN',                'EN');
    define('CMS_FR',                'FR');

/**
 * Lang names
 */
    define('CMS_SERBIAN',           'Srpski');
    define('CMS_ENGLISH',           'English');
    define('CMS_FRENCH',            'Français');

/**
 * User permission
 */
    define('CMS_GUEST',             1);
    define('CMS_USER',              2);
    define('CMS_ADMIN',             4);

/**
 * Fields
 */
    define('CMS_T_TITLE',           'title');
    define('CMS_T_TYPE',            'type');
    define('CMS_T_MODIFY',          'modify');
    define('CMS_T_LIST',            'list');
    define('CMS_T_DEFAULT_VALUE',   'default_value');
    define('CMS_T_VALUE',           'value');
    define('CMS_T_VALUES',          'values');
    define('CMS_T_MIN_VALUE',       'min_value');
    define('CMS_T_MAX_VALUE',       'max_value');