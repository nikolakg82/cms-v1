<?php
/**
 * Created by PhpStorm.
 * User: Nikola
 * Date: 5/3/2016
 * Time: 2:11 PM
 */

use fm\lib\help\ClassLoader, fm\FM;

class CregistryController
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
    private static $controllers;

    /**
     * @var string - Kljuc trenutnog kontrolera
     */
    private static $current;

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
    private static $ch_lang;


    //Geteri
    /**
     * Vraca trenutni kontroler
     * @return string
     */
    public static function get_current()
    {
        return self::$current;
    }

    public static function get_ch_lang()
    {
        return self::$ch_lang;
    }

    /**
     * Dodavanje kontrolera, putanja koja se prosledjuje je putanja do lokalnog MVC-a tj do MVC-a sajta
     * @param string $strKey
     * @param  string $strPathMvc
     * @throws Exception
     */
    public static function add_controller($strKey, $strPathMvc)
    {
        ClassLoader::addClass("M$strKey", $strPathMvc . "/model/m$strKey.php", 'public', "Cm$strKey");
        ClassLoader::addClass('app\lib\mvc\controller\Controller' . ucfirst($strKey), $strPathMvc . "/controller/Controller" . ucfirst($strKey) . ".php", 'public', 'cms\lib\mvc\controller\ControllerNews');
        ClassLoader::addClass("V$strKey", $strPathMvc . "/view/v$strKey.php", 'public', "Cv$strKey");

        self::$controllers[$strKey] = array();
    }

    /**
     * Dodavanje MLC-a za kontrolere, za svaki kontroler se setuje kako se zove na kojem jeziku, podesavanje se vrsi iz konfiguracionog fajla
     */
    public static function add_langs()
    {
        $arrLangs = Clang::get_lang();
        $arrControllers = FM::includer(APP_CONFIG . 'controller.php');

        if(FM::is_variable($arrLangs) && FM::is_variable($arrControllers))
        {
            foreach(self::$controllers as $keyC => $valC)
            {
                foreach($arrLangs as $keyL => $valL)
                {
                    self::$controllers[$keyC]['lang'][$keyL] = $arrControllers[$keyL][$keyC];
                }
            }
        }
    }

    /**
     * Dodavanje tabela svakom kontroleru
     */
    public static function add_tables()
    {
        $arrConfig = FM::includer(APP_CONFIG . 'table.php');

        if(FM::is_variable($arrConfig))
        {
            foreach($arrConfig as $key => $val)
            {
                if(FM::is_variable($val))
                    self::$controllers[$key]['table'] = $val;
            }
        }
    }

    /**
     * Load kontrolera, prosledjuje se kljuc kontrolera, ako postoji path proverava i ako postoje setovane tabele za kontroler radi mini rewrite
     * I bilduje linkove za zamenu jezika
     * @param string $strKey
     * @return object mixed
     * @throws Exception
     */
    public static function load($strKey)
    {
        $objController = ClassLoader::load('app\lib\mvc\controller\Controller' . ucfirst($strKey));
        // @TODO model i view ne idu dinamicki load ka i kontroler, view se izbacuje skroz
        $objModel = ClassLoader::load("M$strKey");

//        $objView = Floader::load("V$strKey");

//        $objController->set_view($objView);
        $objController->setModel($objModel);


        foreach(Clang::get_lang() as $keyLang => $lang)
        {
            if($keyLang != Clang::get_current())
                self::$ch_lang[$keyLang]['path'] = "/" . self::get_name_key_lang(self::get_current(), $keyLang);

            self::$ch_lang[$keyLang]['name'] = Clang::get_lang($keyLang)['name'];
        }

        $strPath = Ffetch::get('path');
        if(FM::is_variable($strPath))
        {
            if(FM::is_variable(self::$controllers[$strKey]['table']))
            {
                foreach(self::$controllers[$strKey]['table'] as $val)
                {
                    $strSql = "SELECT sid, path FROM " . $val ."_mlc WHERE path = :path AND lang = '" . Clang::get_current() . "' LIMIT 1";
                    $arrPrepare[":path"] = $strPath;

                    CMS::$db->query($strSql, $arrPrepare);
                    $arrDataTemp = CMS::$db->fetch(FM_FETCH_ASSOC, false);

                    if(CMS::$db->row_count() > 0)
                    {
                        $objController->set_path($val, $arrDataTemp['sid']);

                        if(FM::is_variable(self::$ch_lang))
                        {
                            $strSql = "SELECT lang, path FROM " . $val ."_mlc WHERE sid = '" . $arrDataTemp['sid'] . "' AND lang != '" . Clang::get_current() . "'";
                            CMS::$db->query($strSql);
                            $arrDataOtherLang = CMS::$db->fetch(FM_FETCH_KEY_PAIR);
                            if(CMS::$db->row_count() > 0)
                            {
                                foreach(self::$ch_lang as $keyData => &$valData)
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

        if(FM::is_variable(self::$ch_lang))
        {
            foreach(self::$ch_lang as &$val)
            {
                if(isset($val['path']))
                {
                    if(FM::is_variable($val['path']) && $val['path'] != "/")
                        $val['path'] .= "." . CMS::$view->get_type();
                }
            }
        }

        return $objController;
    }

    /**
     * u odnosu na naziv kontrolera onoga sto pise u url-u setuje se jezik i setuje se kontroler
     * @param string $strName
     */
    public static function set_curent_lang_controller($strName)
    {
        foreach(self::$controllers as $keyC => $valC)
        {
            foreach($valC['lang'] as $keyL => $valL)
            {
                if($strName == $valL)
                {
                    Clang::set_current($keyL);
                    self::$current = $keyC;
                }
            }
        }
    }

    /**
     *
     * @return array
     */
    public static function get_cotrollers_path_from_lang()
    {
        $arrData = null;

        foreach(self::$controllers as $key => $val)
            $arrData[$key] = $val['lang'][Clang::get_current()];

        return $arrData;
    }

    public static function get_name_key_lang($strKey, $strLang)
    {
        $strName = null;

        if(FM::is_variable(self::$controllers[$strKey]['lang'][$strLang]))
            $strName = self::$controllers[$strKey]['lang'][$strLang];

        return $strName;
    }

    public static function get_controllers($strKey = null)
    {
        $arrData = null;

        if(FM::is_variable($strKey))
        {
            if(isset(self::$controllers[$strKey]))
                $arrData = self::$controllers[$strKey];
        }
        else
            $arrData = self::$controllers;

        return $arrData;
    }
}