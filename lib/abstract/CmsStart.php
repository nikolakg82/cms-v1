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

        /* @TODO load controlera i ceo mvc uraditi malo drugacije, nema view klase to treba da ide u responser, model da ima loader
         *  mozda da bude i statican ali to i nije dobra ideja, bolje da ima loader za model
         */
        $objController = ControllerLoader::load(ControllerLoader::getCurrent());

        $strView = Request::name('view', FM_STRING, FM_GET);

//        var_dump($strView);
        /* @TODO kontroler treba da vrati neki view, treba da se napravi neki objekat response koji ce da ima tip
        *  responsa tako da ovde znamo sta cemo da rendamo, dal ce do da bude html, json, xml ...
        *
        */
        $objResponse = $objController->run();

        if($strView == FM_JSON)
        {
            FM::header('Content-Type: application/json', true, $objResponse->getResponseCode());
            echo json_encode($objResponse->getData());
        }
        else
        {
//        var_dump($objView['data']);
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