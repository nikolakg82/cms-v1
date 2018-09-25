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

use cms\lib\abstracts\Controller;

class ControllerAdmin extends Controller
{
    public function index()
    {
        return $this->getResponse()->setResponseCode(200)->setTemplatePath(CMS_C_ADMIN . '/index.tpl');
    }
}