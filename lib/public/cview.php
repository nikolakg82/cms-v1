<?php

class Cview
{
    /**
     * @var - smarty objekat
     */
    private $smarty;

    /**
     * @var - putanja do templejta
     */
    private $theme;

    /**
     * @var - putanja do smarty kesa
     */
    private $cache;

    /**
     * @var - putanja do smarty compile cache foldera
     */
    private $theme_cache;

    /**
     * @var - Tip prikaza, (HTML, XML, JSON)
     */
    private $type = FM_HTML;

    /**
     * @var - Potrebni podaci za assing u smarty
     */
    private $data;

    /**
     * @var - putanja do templejta koji se prikazuje
     */
    private $display_template;

    //Seteri

    public function set_theme($strPath)
    {
        $this->theme = $strPath;
    }

    public function set_cache($strPath)
    {
        $this->cache = $strPath;
    }

    public function set_theme_cache($strPath)
    {
        $this->theme_cache = $strPath;
    }

    /**
     * Setuje tip prikaza strane
     *
     * @param string $strType - Tip prikaza strane
     */
    public function set_type($strType)
    {
        if(FM::is_variable($strType))
        {
            $arrTypes = FM::includer(CMS_CONFIG . 'view.php', false);

            if(FM::is_variable($arrTypes))
            {
                foreach($arrTypes as $val)
                {
                    if($val == $strType)
                        $this->type = $strType;
                }
            }
        }
    }

    //Geteri

    public function get_theme()
    {
        return $this->theme;
    }

    public function get_cache()
    {
        return $this->cache;
    }

    public function get_theme_cache()
    {
        return $this->theme_cache;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function get_smarty()
    {
        return $this->smarty;
    }

    /**
     * Istanciranje smartija i podesavanje radnih foldra za smarty
     *
     * @throws Exception
     */
    public function load_smarty()
    {
        $this->smarty = Floader::load('SmartyBC');

        $this->smarty->setTemplateDir($this->get_theme());
        $this->smarty->setCompileDir($this->get_theme_cache());
        $this->smarty->setCacheDir($this->get_cache());
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
        if(FM::is_variable($this->display_template))
        {
            //@TODO Izbaciti notice u loger
        }

        $this->display_template = $strPath;
    }

    /**
     * Asajnovanje podataku u smarty i prikaz templejta
     * Nakon toga radi se unsetovanje podatak i templejta da bi mogao ponovo da se radi prikaz
     */
    public function show()
    {
        if(FM::is_variable($this->data))
        {
            foreach($this->data as $key => $val)
            {
                $this->smarty->assign($key, $val);//@TODO ubaciti u loger ove podatke
            }

            unset($this->data);
        }

        if(FM::is_variable($this->display_template))
        {
            $this->smarty->display($this->display_template);//@TODO ubaciti u loger
            unset($this->display_template);
        }
    }

    /**
     * Asajnovanje i prikaz templetjta, ovo je najpogodnije kada se iz smarty templejta poziva neki prikaz
     *
     * @param $strTheme - putanja do templejta
     * @param $arrData - Podaci za prikaz
     * @param string $strKey - Kljuc pod kojim ce se asajnovati podaci, po defaultu je data
     */
    public function show_data($strTheme, $arrData, $strKey = "data")
    {
        $this->assign($strKey, $arrData);
        $this->display($strTheme);

        $this->show();
    }
}