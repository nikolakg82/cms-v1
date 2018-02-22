<?php

namespace cms\lib\abstracts;

use cms\CMS;
use cms\lib\help\ControllerLoader;
use cms\lib\help\Lang;
use fm\FM;
use fm\lib\help\Request;
use fm\lib\publisher\Response;

abstract class CmsStart
{
    /**
     * @var \cms\lib\interfaces\Controller
     */
    protected $activeController;

    /**
     * @var Response
     */
    protected $response;

    public function __construct()
    {
        ControllerLoader::setCurrentLangController(Request::name('controller', FM_STRING, FM_GET));
    }

    public function run()
    {
        CMS::setView();
        CMS::setSiteDomain();
        CMS::setGlobalConfig();

        $strView = Request::name('view', FM_STRING, FM_GET);

        if(empty($strView))
            $strView = FM_HTML;

        $this->activeController = ControllerLoader::load(ControllerLoader::getCurrent());
        $this->response = $this->activeController->run();

        $arrResponseCode = FM::includer(FM_CONFIG . 'responseCode.php', false);

        if(isset($arrResponseCode[$this->response->getResponseCode()]))
            FM::header($arrResponseCode[$this->response->getResponseCode()], true, $this->response->getResponseCode());

        if($strView == FM_JSON)
        {
            FM::header('Content-Type: application/json', true, $this->response->getResponseCode());
            echo json_encode($this->response->getData());
        }
        else
        {
            CMS::$view->assign('data', $this->response->getData());
            CMS::$view->display($this->response->getTemplatePath());

            CMS::$view->assign("lab", Lang::getLab());
            CMS::$view->assign("config", CMS::getGlobalConfig());
            CMS::$view->assign('domain', CMS::getSiteDomain());
            CMS::$view->assign('ch_lang', ControllerLoader::getChLang());
            CMS::$view->assign('lang', Lang::getCurrent());
            CMS::$view->assign('controllers', ControllerLoader::getControllersPathFromLang());
            CMS::$view->assign('controller', ControllerLoader::getCurrent());
            CMS::$view->assign('admin_theme', CMS::getAdminTheme());
            CMS::$view->assign('langs', Lang::getLang());

            CMS::$view->show();
        }
    }
}