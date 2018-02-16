<?php

abstract class CmsStart
{
    public function __construct()
    {
        CregistryController::set_curent_lang_controller(Ffetch::name('controller', FM_STRING, FM_GET));
    }

    public function run()
    {
        CMS::set_view();
        CMS::set_site_domain();
        CMS::set_global_config();

        /* @TODO load controlera i ceo mvc uraditi malo drugacije, nema view klase to treba da ide u responser, model da ima loader
         *  mozda da bude i statican ali to i nije dobra ideja, bolje da ima loader za model
         */
        $objController = CregistryController::load(CregistryController::get_current());

        $strView = Ffetch::name('view', FM_STRING, FM_GET);

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

            CMS::$view->assign("lab", Clang::get_lab());
            CMS::$view->assign("config", CMS::get_global_config());
            CMS::$view->assign('domain', CMS::get_site_domain());
            CMS::$view->assign('ch_lang', CregistryController::get_ch_lang());
            CMS::$view->assign('lang', Clang::get_current());
            CMS::$view->assign('controllers', CregistryController::get_cotrollers_path_from_lang());
            CMS::$view->assign('controller', CregistryController::get_current());
            CMS::$view->assign('admin_theme', CMS::get_admin_theme());
            CMS::$view->assign('langs', Clang::get_lang());



            CMS::$view->show();
        }
    }
}