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
use fm\lib\help\Request;

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
     * @param  string $strPathMvc
     * @throws \Exception
     */
    public static function addController($strKey, $strPathMvc)
    {
        ClassLoader::addClass('app\lib\mvc\model\Model' . ucfirst($strKey),
                                $strPathMvc . "/model/Model" . ucfirst($strKey) . ".php", 'public',
                                'cms\lib\mvc\model\Model' . ucfirst($strKey));

        ClassLoader::addClass('app\lib\mvc\controller\Controller' . ucfirst($strKey),
                                $strPathMvc . "/controller/Controller" . ucfirst($strKey) . ".php", 'public',
                                'cms\lib\mvc\controller\Controller' . ucfirst($strKey));

//        ClassLoader::addClass("V$strKey", $strPathMvc . "/view/v$strKey.php", 'public', "Cv$strKey");

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
                    self::$controllers[$keyC]['lang'][$keyL] = $arrControllers[$keyL][$keyC];
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
                if(isset($val))
                    self::$controllers[$key]['table'] = $val;
            }
        }
    }

    /**
     * Load kontrolera, prosledjuje se kljuc kontrolera, ako postoji path proverava i ako postoje setovane tabele za kontroler radi mini rewrite
     * I bilduje linkove za zamenu jezika
     * @param string $strKey
     * @return object mixed
     */
    public static function load($strKey)
    {
        $objController = ClassLoader::load('app\lib\mvc\controller\Controller' . ucfirst($strKey));
        // @TODO model i view ne idu dinamicki load ka i kontroler, view se izbacuje skroz
        $objModel = ClassLoader::load('cms\lib\mvc\model\Model' . ucfirst($strKey));

//        $objView = Floader::load("V$strKey");

//        $objController->set_view($objView);
        $objController->setModel($objModel);


        foreach(Lang::getLang() as $keyLang => $lang)
        {
            if($keyLang != Lang::getCurrent())
                self::$chLang[$keyLang]['path'] = "/" . self::getNameKeyLang(self::getCurrent(), $keyLang);

            self::$chLang[$keyLang]['name'] = Lang::getLang($keyLang)['name'];
        }

        $strPath = Request::get('path');
        if(isset($strPath))
        {
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
                        $objController->set_path($val, $arrDataTemp['sid']);

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

        return $objController;
    }

    /**
     * u odnosu na naziv kontrolera onoga sto pise u url-u setuje se jezik i setuje se kontroler
     * @param string $strName
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