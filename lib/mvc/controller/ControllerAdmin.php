<?php

namespace cms\lib\mvc\controller;

use cms\lib\abstracts\Controller;

class ControllerAdmin extends Controller
{
    public function index()
    {
        return $this->getResponse()->setResponseCode(200)->setTemplatePath(CMS_C_ADMIN . '/index.tpl');
    }
}