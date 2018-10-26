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
use cms\lib\publisher\AdminWorker;
use fm\FM;
use fm\lib\help\ClassLoader;

class ControllerAdmin extends Controller
{
    /**
     * @var AdminWorker
     */
    protected $adminWorker;

    public function index()
    {
        $arrChapters = $this->adminWorker->getStructure();
        return $this->getResponse()->setResponseCode(200)->setTemplatePath(CMS_C_ADMIN . '/index.tpl')->setData($arrChapters);
    }

    public function __construct()
    {
        $this->adminWorker = ClassLoader::load('cms\lib\publisher\AdminWorker');
    }
}