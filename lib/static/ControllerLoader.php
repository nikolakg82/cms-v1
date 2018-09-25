<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 2:11 PM
 */

namespace cms\lib\help;

use cms\CMS;
use fm\lib\help\ClassLoader, fm\FM;
use fm\lib\help\Numeric;
use fm\lib\help\Request;
use fm\lib\help\Router;
use fm\lib\help\Stringer;

class ControllerLoader
{
    /**
     * @var array - Registry controllers
     * @TODO Check is example correct
     *
     * Example: array(
     *                       'controller_key' => array(
     *                                                      'lang' => array(
     *                                                                          'lang_key' => 'Title'
     *                                                                      ),
     *                                                      'table' => array(
     *                                                                          'Table name'
     *                                                                       ),
     *                                                      'routes' => array(
     *                                                                          'route_path'    => array(
     *                                                                                                     'method' => 'request method',
     *                                                                                                     'function' => 'controller's method name'
     *                                                                                                   ),
     *                                                                        ),
     * ),
     *                                                  ),
     *                  );
     */
    protected static $controllers;

    /**
     * @var string - Key current controller
     */
    protected static $current;

    /**
     * @var array - Array for change lang of site on current page
     *
     * Example: array(
     *                      'lang_key' => array(
     *                                              'path' => 'path',
     *                                              'name' => 'Lang title'
     *                                       ),
     *                      );
     */
    protected static $chLang;

    /**
     * Get key current controller
     *
     * @return string
     */
    public static function getCurrent()
    {
        return self::$current;
    }

    /**
     * Get array for lang change
     *
     * @return array
     */
    public static function getChLang()
    {
        return self::$chLang;
    }

    /**
     * Add controller
     *
     * @param string $strKey - Controller key
     * @param string $strPathMvc - Path of MVC folder (local MVC on the app)
     * @param string $strExtendClassController - Class witch app controller extended
     * @param string $strExtendClassModel - Class witch app model extended
     * @throws \Exception
     */
    public static function addController($strKey, $strPathMvc, $strExtendClassController, $strExtendClassModel)
    {
        ClassLoader::addClass('app\lib\mvc\model\Model' . ucfirst($strKey),
                                $strPathMvc . "/model/Model" . ucfirst($strKey) . ".php", 'public',
                                            $strExtendClassModel);

        ClassLoader::addClass('app\lib\mvc\controller\Controller' . ucfirst($strKey),
                                $strPathMvc . "/controller/Controller" . ucfirst($strKey) . ".php", 'public',
                                            $strExtendClassController);

        self::$controllers[$strKey] = array();
    }

    /**
     * Setup controller name for each lang
     */
    public static function addLang()
    {
        $arrLang = Lang::getLang();
        $arrControllers = FM::includer(APP_CONFIG . 'controller.php');

        if(isset($arrLang) && isset($arrControllers))
        {
            foreach(self::$controllers as $keyC => $valC)
            {
                foreach($arrLang as $keyL => $valL)
                {
                    self::$controllers[$keyC]['lang'][$keyL] = $arrControllers[$keyL][$keyC];
                }
            }
        }
    }

    /**
     * Add table for each controller
     */
    public static function addTables()
    {
        $arrConfig = FM::includer(APP_CONFIG . 'table.php');

        if(isset($arrConfig))
        {
            foreach($arrConfig as $key => $val)
            {
                if(isset($val) && isset(self::$controllers[$key]))
                    self::$controllers[$key]['table'] = $val;
            }
        }
    }

    /**
     * Load controller
     *
     * @param string $strKey - Controller key
     * @param boolean $boolRouter - If true return response object
     * @return object mixed
     */
    public static function load($strKey, $boolRouter = false)
    {
        $objController = ClassLoader::load('app\lib\mvc\controller\Controller' . ucfirst($strKey));
        $objModel = ClassLoader::load('app\lib\mvc\model\Model' . ucfirst($strKey));
        $objResponse = ClassLoader::load('fm\lib\publisher\Response');

        $objController->setModel($objModel)->setResponse($objResponse);

        if($boolRouter)
        {
            foreach(Lang::getLang() as $keyLang => $lang)
            {
                if($keyLang != Lang::getCurrent())
                    self::$chLang[$keyLang]['path'] = "/" . self::getNameKeyLang(self::getCurrent(), $keyLang);

                self::$chLang[$keyLang]['name'] = Lang::getLang($keyLang)['name'];
            }


            $strRoute = "/";

            $strPath = Request::get('path');

            if(isset($strPath))
            {
                $strRoute = "/$strPath";

                if(isset(self::$controllers[$strKey]['table']))
                {
                    foreach(self::$controllers[$strKey]['table'] as $val)
                    {
                        $strSql = "SELECT sid, path FROM " . $val ."_mlc WHERE path = :path AND lang = '" . Lang::getCurrent() . "' LIMIT 1";
                        $arrPrepare[":path"] = $strPath;

                        CMS::$db->query($strSql, $arrPrepare);
                        $arrDataTemp = CMS::$db->fetch(FM_FETCH_ASSOC, false);

                        if(CMS::$db->rowCount() > 0)
                        {
                            $objController->setPath($val, $arrDataTemp['sid']);

                            if(isset(self::$chLang))
                            {
                                $strSql = "SELECT lang, path FROM " . $val ."_mlc WHERE sid = '" . $arrDataTemp['sid'] . "' AND lang != '" . Lang::getCurrent() . "'";
                                CMS::$db->query($strSql);
                                $arrDataOtherLang = CMS::$db->fetch(FM_FETCH_KEY_PAIR);
                                if(CMS::$db->rowCount() > 0)
                                {
                                    foreach(self::$chLang as $keyData => &$valData)
                                    {
                                        if(isset($valData['path']) && isset($arrDataOtherLang[$keyData]))
                                            $valData['path'] .= "/" . $arrDataOtherLang[$keyData];
                                    }
                                }
                            }
                            break;
                        }
                    }
                }
            }

            if(isset(self::$chLang))
            {
                foreach(self::$chLang as &$val)
                {
                    if(isset($val['path']) && $val['path'] != "/")
                        $val['path'] .= "." . CMS::$view->getType();
                }
            }

            FM::includer(APP_ROUTES . $strKey . ".php");

            $arrRouteData = Router::getRouteDetails($strKey, $strRoute);

            if(isset($arrRouteData))
            {
                $strFunctionName = $arrRouteData['function'];

                if(CMS::$userPermission & $arrRouteData['permission'])
                {
                    if(!isset($arrRouteData['params']))
                        $arrRouteData['params'] = array();

                    $objReturn = call_user_func_array(array($objController, $strFunctionName), $arrRouteData['params']);
                }
                else
                    $objReturn = $objResponse->setResponseCode(401)->setTemplatePath(CMS_C_STRUCTURE . '/401.tpl');
            }
            else
                $objReturn = $objResponse->setResponseCode(404)->setTemplatePath(CMS_C_STRUCTURE . '/404.tpl');
        }
        else
            $objReturn = $objController;

        return $objReturn;
    }

    /**
     * Setup current lang and controller
     *
     * @param string $strName - Controller title (from path)
     */
    public static function setCurrentLangController($strName)
    {
        foreach(self::$controllers as $keyC => $valC)
        {
            foreach($valC['lang'] as $keyL => $valL)
            {
                if($strName == $valL)
                {
                    Lang::setCurrent($keyL);
                    self::$current = $keyC;
                }
            }
        }
    }
    
    /**
     *
     * @return array
     */
    public static function getControllersPathFromLang()
    {
        $arrData = null;

        foreach(self::$controllers as $key => $val)
            $arrData[$key] = $val['lang'][Lang::getCurrent()];

        return $arrData;
    }

    public static function getNameKeyLang($strKey, $strLang)
    {
        $strName = null;

        if(isset(self::$controllers[$strKey]['lang'][$strLang]))
            $strName = self::$controllers[$strKey]['lang'][$strLang];

        return $strName;
    }

    public static function getControllers($strKey = null)
    {
        $arrData = null;

        if(isset($strKey))
        {
            if(isset(self::$controllers[$strKey]))
                $arrData = self::$controllers[$strKey];
        }
        else
            $arrData = self::$controllers;

        return $arrData;
    }
}