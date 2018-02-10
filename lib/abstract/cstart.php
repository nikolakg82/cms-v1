<?php

abstract class Cstart
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

        $objController = CregistryController::load(CregistryController::get_current());

        CMS::$view->assign("lab", Clang::get_lab());
        CMS::$view->assign("config", CMS::get_global_config());
        CMS::$view->assign('domain', CMS::get_site_domain());
        CMS::$view->assign('ch_lang', CregistryController::get_ch_lang());
        CMS::$view->assign('lang', Clang::get_current());
        CMS::$view->assign('controllers', CregistryController::get_cotrollers_path_from_lang());
        CMS::$view->assign('controller', CregistryController::get_current());
        CMS::$view->assign('admin_theme', CMS::get_admin_theme());
        CMS::$view->assign('langs', Clang::get_lang());

        $objController->run();

        CMS::$view->show();
    }
}