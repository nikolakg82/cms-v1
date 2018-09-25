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
     * Instance and setup smarty template engine
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
     * Assign variable in smarty
     *
     * @param string $strVarName - Name of variable
     * @param mixed $mixData - Value of variable
     * @param boolean $boolOverwrite - is overwrite previous value
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
            //@TODO add error to logger
        }
    }

    //@TODO Check point for this method
    public function append($strVarName, $mixData)
    {
        $this->smarty->append($strVarName, $mixData);
    }

    /**
     * Add template for show, can be only one
     *
     * @param $strPath
     */
    public function display($strPath)
    {
        if(isset($this->displayTemplate))
        {
            //@TODO add error in logger
        }

        $this->displayTemplate = $strPath;
    }

    /**
     * Show template with assigned data, after that unset all data
     */
    public function show()
    {
        if(!empty($this->data))
        {
            foreach($this->data as $key => $val)
            {
                $this->smarty->assign($key, $val);//@TODO add in logger this data
            }

            unset($this->data);
        }

        if(!empty($this->displayTemplate))
        {
            $this->smarty->display($this->displayTemplate);//@TODO add in logger this data
            unset($this->displayTemplate);
        }
    }

    /**
     * Assign variable to smarty and show templates, useful to call from another template
     *
     * @param $strTheme - Path to template
     * @param $arrData - Data for show
     * @param string $strKey - Key for assigned data
     */
    public function showData($strTheme, $arrData, $strKey = "data")
    {
        $this->assign($strKey, $arrData);
        $this->display($strTheme);

        $this->show();
    }
}