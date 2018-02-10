<?php

class Ccindex extends Ccontroller
{
    public function run()
    {
        CMS::$view->display('index/index.tpl');
//        $this->get_view()->display('index/index.tpl');
    }
}