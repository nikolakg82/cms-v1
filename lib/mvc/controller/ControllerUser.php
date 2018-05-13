<?php

namespace cms\lib\mvc\controller;

use cms\CMS;
use cms\lib\abstracts\Controller;
use fm\FM;
use fm\lib\help\Numeric;
use fm\lib\help\Request;

class ControllerUser extends Controller
{
    public function login()
    {
        $strEmail = Request::post('email');
        $strPassword = Request::post('password');
        $objResponse = $this->getResponse();

        $intResponseCode = 200;
        $mixLoginData = $this->getModel()->login($strEmail, $strPassword);

        if(!isset($mixLoginData))
            $intResponseCode = 401;

        if(CMS::$viewFormat === FM_JSON)
            $objResponse = $objResponse->setData($mixLoginData)->setResponseCode($intResponseCode);
        else
        {
            // @TODO if login ok, set header and redirect to previous page else show login page with message
        }

        return $objResponse;
    }

    public function logout()
    {
        $objResponse = $this->getResponse();
        $intResponseCode = 200;
        $boolStatus = false;

        $strToken = FM::getCustomHttpHeader('token');

        if(isset($strToken))
        {
            $intUserId = FM::getCustomHttpHeader('user');

            if(isset($intUserId))
            {
                $intUserId = Numeric::intVal($intUserId);

                $boolStatus = $this->getModel()->logout($strToken, $intUserId);
            }
        }

        if(CMS::$viewFormat === FM_JSON)
            $objResponse = $objResponse->setData($boolStatus)->setResponseCode($intResponseCode);
        else
        {
            // @TODO in each case show login page
        }

        return $objResponse;
    }
}