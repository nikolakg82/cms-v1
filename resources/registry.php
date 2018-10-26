<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 2:16 PM
 */

use fm\lib\help\ClassLoader, fm\lib\help\InterfaceLoader;

/**
 * Add interfaces
 */
InterfaceLoader::addItem('Controller',     CMS_INTERFACE . 'Controller.php');
InterfaceLoader::addItem('Model',          CMS_INTERFACE . 'Model.php');

/**
 * Static class
 */
ClassLoader::addClass('ControllerLoader',CMS_STATIC . 'ControllerLoader.php');
ClassLoader::addClass('Lang',              CMS_STATIC . 'Lang.php');
ClassLoader::addClass('RegistryAdmin',    CMS_STATIC . 'RegistryAdmin.php');

/**
 * Abstract class
 */
ClassLoader::addClass('cms\lib\abstracts\CmsStart',           CMS_ABSTRACT . 'CmsStart.php',       'abstract');
ClassLoader::addClass('cms\lib\abstracts\Controller',        CMS_ABSTRACT . 'Controller.php',    'abstract', null, ['Controller']);
ClassLoader::addClass('cms\lib\abstracts\Model',             CMS_ABSTRACT . 'Model.php',         'abstract', null, ['Model']);

/**
 * Public class
 */
ClassLoader::addClass('Smarty',             CMS_SMARTY . 'Smarty.class.php',      'public');
ClassLoader::addClass('SmartyBC',           CMS_SMARTY . 'SmartyBC.class.php',    'public');

ClassLoader::addClass('cms\lib\publisher\View',              CMS_PUBLIC . 'View.php',             'public');
ClassLoader::addClass('cms\lib\publisher\AdminWorker',       CMS_PUBLIC . 'AdminWorker.php',             'public');

ClassLoader::addClass('cms\lib\mvc\controller\ControllerIndex',  CMS_CONTROLLER . 'ControllerIndex.php','public',        'cms\lib\abstracts\Controller');
ClassLoader::addClass('cms\lib\mvc\controller\ControllerUser',  CMS_CONTROLLER . 'ControllerUser.php','public',        'cms\lib\abstracts\Controller');
ClassLoader::addClass('cms\lib\mvc\controller\ControllerAdmin',  CMS_CONTROLLER . 'ControllerAdmin.php','public',        'cms\lib\abstracts\Controller');
ClassLoader::addClass('cms\lib\mvc\controller\ControllerNews',  CMS_CONTROLLER . 'ControllerNews.php','public',        'cms\lib\abstracts\Controller');

ClassLoader::addClass('cms\lib\mvc\model\ModelIndex',             CMS_MODEL . 'ModelIndex.php',             'public',        'cms\lib\abstracts\Model');
ClassLoader::addClass('cms\lib\mvc\model\ModelNews',             CMS_MODEL . 'ModelNews.php',             'public',        'cms\lib\abstracts\Model');
ClassLoader::addClass('cms\lib\mvc\model\ModelUser',             CMS_MODEL . 'ModelUser.php',             'public',        'cms\lib\abstracts\Model');
ClassLoader::addClass('cms\lib\mvc\model\ModelAdmin',             CMS_MODEL . 'ModelAdmin.php',             'public',        'cms\lib\abstracts\Model');


//ClassLoader::addClass('Cmadmin',            CMS_MODEL . 'cmadmin.php',            'public',        'Cmodel');
//ClassLoader::addClass('Ccadmin',            CMS_CONTROLLER . 'ccadmin.php',       'public',        'Controller');
//ClassLoader::addClass('Cvadmin',            CMS_VIEW . 'cvadmin.php',             'public',        'Ccview');

//Load static class
ClassLoader::loadStaticClass();