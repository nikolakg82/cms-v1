<?php

/**
 * Created by PhpStorm.
 * User: Nikola
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
     * @var array - Registar controlera
     *
     * Tip niza je array(
     *                       'Kljuc kontrolera' => array(
     *                                                      'lang' => array(
     *                                                                          'Kljuc jezika' => 'Naziv'
     *                                                                      ),
     *                                                      'table' => array(
     *                                                                          'Naziv tabele'
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
     * @var string - Kljuc trenutnog kontrolera
     */
    protected static $current;

    /**
     * @var array - Niz za promenu jezika na sajtu
     *
     * Tip niza je array(
     *                      'Kljuc jezika' => array(
     *                                              'path' => 'Putanja',
     *                                              'name' => 'Naziv jezika'
     *                                       ),
     *                      );
     */
    protected static $chLang;


    //Geteri
    /**
     * Vraca trenutni kontroler
     * @return string
     */
    public static function getCurrent()
    {
        return self::$current;
    }

    public static function getChLang()
    {
        return self::$chLang;
    }

    /**
     * Dodavanje kontrolera, putanja koja se prosledjuje je putanja do lokalnog MVC-a tj do MVC-a sajta
     * @param string $strKey
     * @param string $strPathMvc
     * @param string $strExtendClassController
     * @param string $strExtendClassModel
     * @throws \Exception
     */
    public static function addController($strKey, $strPathMvc, $strExtendClassController, $strExtendClassModel)
    {
//    var_dump($strKey);
        ClassLoader::addClass('app\lib\mvc\model\Model' . ucfirst($strKey),
                                $strPathMvc . "/model/Model" . ucfirst($strKey) . ".php", 'public',
                                            $strExtendClassModel);

        ClassLoader::addClass('app\lib\mvc\controller\Controller' . ucfirst($strKey),
                                $strPathMvc . "/controller/Controller" . ucfirst($strKey) . ".php", 'public',
                                            $strExtendClassController);

        self::$controllers[$strKey] = array();
    }

    /**
     * Dodavanje MLC-a za kontrolere, za svaki kontroler se setuje kako se zove na kojem jeziku, podesavanje se vrsi iz konfiguracionog fajla
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
     * Dodavanje tabela svakom kontroleru
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
     * Load kontrolera, prosledjuje se kljuc kontrolera, ako postoji path proverava i ako postoje setovane tabele za kontroler radi mini rewrite
     * I bilduje linkove za zamenu jezika
     * @param string $strKey
     * @param boolean $boolRouter
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

//                $arrTemp = explode("/", $strPath);
//
//                $boolSetRoute = false;
//                foreach(self::$controllers[$strKey]['routes'] as $route => $routeVal)
//                {
//                    $stringRoute = Stringer::subStr($route, 1);
//
//                    if(!empty($stringRoute))
//                    {
//                        $arrRoute = explode("/", Stringer::subStr($route, 1));
//
//                        foreach($arrRoute as $keyRoute => $oneRoute)
//                        {
//                            if(!Stringer::findCharacter($oneRoute, "?"))
//                            {
//                                if(!isset($arrTemp[$keyRoute]))
//                                {
//                                    $boolSetRoute = false;
//                                    break;
//                                }
//                            }
//                            else
//                                $oneRoute = Stringer::strReplace($oneRoute, "?", "");
//
//                            $regex = '/{(.*)}/';
//                            preg_match_all($regex, $oneRoute, $matches);
//
//                            if(!empty($matches[1]))
//                            {
//                                $strParameterType = FM_STRING;
//
//                                $arrTypeTemp = explode("|", $matches[1][0]);
//
//                                if(isset($arrTypeTemp[1]))
//                                    $strParameterType = $arrTypeTemp[1];
//
//                                if(isset($arrTemp[$keyRoute]))
//                                {
//                                    if($strParameterType == FM_INTEGER)
//                                    {
//                                        $tempVal = Numeric::intVal($arrTemp[$keyRoute]);
//
//                                        if(Numeric::isInt($tempVal))
//                                        {
//                                            $boolSetRoute = true;
//                                        }
//                                        else
//                                        {
//                                            $boolSetRoute = false;
//                                            break;
//                                        }
//
//                                    }
//                                    elseif($strParameterType == FM_STRING)
//                                    {
//                                        if(Stringer::isString($arrTemp[$keyRoute]))
//                                            $boolSetRoute = true;
//                                    }
//                                }
//                            }
//                            else
//                            {
//                                if($oneRoute != $arrTemp[$keyRoute])
//                                {
//                                    $boolSetRoute = false;
//                                    break;
//                                }
//                                else
//                                {
//                                    $boolSetRoute = true;
//                                }
//                            }
//
//                        }
//
//                        if($boolSetRoute)
//                        {
//                            $activeRoute = $route;
//                            break;
//                        }
//                    }
//                }

//                if(isset($activeRoute))
//                    $strRoute = $activeRoute;
//                else
//                    var_dump("555");

//                die();



//                if(isset(self::$controllers[$strKey]['routes'][$activeRoute][FM::requestMethod()]))
//                {
//                    $strRoute = "/$strPath";
//                }
//                else
//                {
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
//                }
            }

            if(isset(self::$chLang))
            {
                foreach(self::$chLang as &$val)
                {
                    if(isset($val['path']) && $val['path'] != "/")
                        $val['path'] .= "." . CMS::$view->getType();
                }
            }

            $arrRouteData = Router::getRouteDetails($strKey, $strRoute);

            if(isset($arrRouteData))
            {
                $strFunctionName = $arrRouteData['function'];

                if(CMS::$userPermission & $arrRouteData['permission'])
                    $objReturn = $objController->$strFunctionName();
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
     * u odnosu na naziv kontrolera onoga sto pise u url-u setuje se jezik i setuje se kontroler
     * @param string $strName
     */
    public static function setCurrentLangController($strName)
    {
//    var_dump(self::$controllers);
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

    public static function setRoutes()
    {
        foreach(self::$controllers as $key => $val)
        {
            $arrRoutes = Router::getRoutesFromController($key);

            if(isset($arrRoutes))
            {
                foreach($arrRoutes as $strRoute => $arrRouteData)
                {
                    foreach($arrRouteData as $strMethod => $arrDetails)
                    {
                        self::$controllers[$key]['routes'][$strRoute][$strMethod] = array(
                                                            'function' => $arrDetails['function']
                                                            );
                    }
                }
            }
        }

//        var_dump(self::$controllers);
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