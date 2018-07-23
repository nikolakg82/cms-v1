<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 2/16/2018
 * Time: 1:58 PM
 */

namespace cms\lib\publisher;

use fm\FM;
use fm\lib\help\ClassLoader;

class View
{
    /**
     * @var - Smarty object
     */
    protected $smarty;

    /**
     * @var - Path to templates
     */
    protected $theme;

    /**
     * @var - Path to smarty cache
     */
    protected $cache;

    /**
     * @var - Path smarty compile cache
     */
    protected $themeCache;

    /**
     * @var - Show type, (HTML, XML, JSON)
     */
    protected $type = FM_HTML;

    /**
     * @var - Data for assign in smarty
     */
    protected $data;

    /**
     * @var - Path to templates
     */
    protected $displayTemplate;

    public function setTheme($strPath)
    {
        $this->theme = $strPath;
    }

    public function setCache($strPath)
    {
        $this->cache = $strPath;
    }

    public function setThemeCache($strPath)
    {
        $this->themeCache = $strPath;
    }

    /**
     * Set show type
     *
     * @param string $strType - Show type
     */
    public function setType($strType)
    {
        if(!empty($strType))
        {
            $arrTypes = FM::includer(CMS_CONFIG . 'view.php', false);

            if(isset($arrTypes))
            {
                foreach($arrTypes as $val)
                {
                    if($val == $strType)
                        $this->type = $strType;
                }
            }
        }
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function getThemeCache()
    {
        return $this->themeCache;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSmarty()
    {
        return $this->smarty;
    }

    /**
     * Istanciranje smartija i podesavanje radnih foldra za smarty
     *
     * @throws \Exception
     */
    public function loadSmarty()
    {
        $this->smarty = ClassLoader::load('SmartyBC');

        $this->smarty->setTemplateDir($this->getTheme());
        $this->smarty->setCompileDir($this->getThemeCache());
        $this->smarty->setCacheDir($this->getCache());
    }

    /**
     * Asajnuje varijable u smarty
     *
     * @param string $strVarName - naziv valijable
     * @param mixed $mixData - vrednost varijable
     * @param boolean $boolOverwrite - ako je true gazi se prethodna vresnost
     */
    public function assign($strVarName, $mixData, $boolOverwrite = false)
    {
        $boolAdded = true;

        if(isset($this->data[$strVarName]) && !$boolOverwrite)
            $boolAdded = false;

        if($boolAdded)
            $this->data[$strVarName] = $mixData;
        else
        {
            //@TODO ovo treba da se stavi u logger greska
        }
    }

    //@TODO za ovo napisati pravu logiku da bi imalo namenu
    public function append($strVarName, $mixData)
    {
        $this->smarty->append($strVarName, $mixData);
    }

    /**
     * Dodavanje templejta koji ce se prikazati, moze da ima samo jedan
     *
     * @param $strPath
     */
    public function display($strPath)
    {
        if(isset($this->displayTemplate))
        {
            //@TODO Izbaciti notice u loger
        }

        $this->displayTemplate = $strPath;
    }

    /**
     * Asajnovanje podataku u smarty i prikaz templejta
     * Nakon toga radi se unsetovanje podatak i templejta da bi mogao ponovo da se radi prikaz
     */
    public function show()
    {
        if(!empty($this->data))
        {
            foreach($this->data as $key => $val)
            {
                $this->smarty->assign($key, $val);//@TODO ubaciti u loger ove podatke
            }

            unset($this->data);
        }

        if(!empty($this->displayTemplate))
        {
            $this->smarty->display($this->displayTemplate);//@TODO ubaciti u loger
            unset($this->displayTemplate);
        }
    }

    /**
     * Asajnovanje i prikaz templetjta, ovo je najpogodnije kada se iz smarty templejta poziva neki prikaz
     *
     * @param $strTheme - putanja do templejta
     * @param $arrData - Podaci za prikaz
     * @param string $strKey - Kljuc pod kojim ce se asajnovati podaci, po defaultu je data
     */
    public function showData($strTheme, $arrData, $strKey = "data")
    {
        $this->assign($strKey, $arrData);
        $this->display($strTheme);

        $this->show();
    }
}