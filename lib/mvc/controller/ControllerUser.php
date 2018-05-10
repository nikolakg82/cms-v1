<?php

namespace cms\lib\mvc\controller;

use cms\CMS;
use cms\lib\abstracts\Controller;
use fm\lib\help\Request;

class ControllerUser extends Controller
{
    public function login()
    {
        $strEmail = Request::post('email');
        $strPassword = Request::post('password');

        $mixLoginData = $this->getModel()->login($strEmail, $strPassword);

        if(CMS::$viewFormat === FM_JSON)
            return $this->getResponse()->setData($mixLoginData)->setResponseCode(200);
    }
}