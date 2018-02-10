<?php

class Cvadmin extends Ccview
{
    public function login_form()
    {
        CMS::$view->display(CMS::get_admin_theme() . CMS_C_ADMIN . '/login.tpl');
    }

    public function index()
    {
        CMS::$view->display(CMS::get_admin_theme() . CMS_C_ADMIN . '/index.tpl');
    }

    public function list_data($arrTableData, $strTable, $boolPagination = true, $intSid = null)
    {
        $arrData = $this->get_model()->list_data($arrTableData, $strTable, $boolPagination, $intSid);
        CMS::$view->assign('data', $arrData);
        CMS::$view->display(CMS::get_admin_theme() . CMS_C_ADMIN . '/list_data.tpl');
    }
}