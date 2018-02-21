<?php

namespace cms\lib\abstracts;

use cms\CMS;
use cms\lib\help\ControllerLoader;
use cms\lib\help\Lang;
use fm\FM;
use fm\lib\help\Request;

abstract class CmsStart
{
    public function __construct()
    {
        ControllerLoader::setCurrentLangController(Request::name('controller', FM_STRING, FM_GET));
    }

    public function run()
    {
        CMS::setView();
        CMS::setSiteDomain();
        CMS::setGlobalConfig();

        $objController = ControllerLoader::load(ControllerLoader::getCurrent());

        $strView = Request::name('view', FM_STRING, FM_GET);

        if(empty($strView))
            $strView = FM_HTML;

        $objResponse = $objController->run();

        if($strView == FM_JSON)
        {
            FM::header('Content-Type: application/json', true, $objResponse->getResponseCode());
            echo json_encode($objResponse->getData());
        }
        else
        {
            CMS::$view->assign('data', $objResponse->getData());
            CMS::$view->display($objResponse->getTemplatePath());

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