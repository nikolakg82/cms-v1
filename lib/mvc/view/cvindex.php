<?php

class Cvindex extends Ccview
{
    public function index()
    {
        CMS::$view->display(CMS_C_INDEX . '/index.tpl');
    }
}