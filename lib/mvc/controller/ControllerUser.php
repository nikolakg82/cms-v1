<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 2:16 PM
 */

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
            $objResponse = $objResponse->setData(FM::referer())->setResponseCode(301);
        }

        return $objResponse;
    }

    public function logout()
    {
        $objResponse = $this->getResponse();
        $intResponseCode = 200;
        $boolStatus = false;

        $strToken = FM::getCustomHttpHeader('token');
        $intUserId = FM::getCustomHttpHeader('user');
        if(isset($intUserId))
            $intUserId = Numeric::intVal($intUserId);

        $boolStatus = $this->getModel()->logout($strToken, $intUserId);

        if(CMS::$viewFormat === FM_JSON)
            $objResponse = $objResponse->setData($boolStatus)->setResponseCode($intResponseCode);
        else
        {
            $objResponse = $objResponse->setData(FM::referer())->setResponseCode(301);
        }

        return $objResponse;
    }

    public function index()
    {
        $strTemplate = 'index.tpl';
        if(CMS::$userPermission == 1)
            $strTemplate = 'login.tpl';

        return $this->getResponse()->setResponseCode(200)->setTemplatePath(CMS_C_USER . "/$strTemplate");
    }

    public function getLogin()
    {
        return $this->getResponse()->setResponseCode(200)->setTemplatePath(CMS_C_USER . '/login.tpl');
    }

    public function getAdminUser()
    {
        return $this->getResponse()->setData(['status' => false])->setResponseCode(200);
    }
}