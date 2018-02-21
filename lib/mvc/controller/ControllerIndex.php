<?php

namespace cms\lib\mvc\controller;

use cms\lib\abstracts\Controller;

class ControllerIndex extends Controller
{
    public function run()
    {
        $objResponse = $this->getResponse()->setResponseCode(200)->setTemplatePath(CMS_C_INDEX . '/index.tpl');

        return $objResponse;
    }
}