<?php

use fm\lib\help\ClassLoader, fm\lib\help\InterfaceLoader;

/**
 * Add interfaces
 */
InterfaceLoader::addItem('Controller',     CMS_INTERFACE . 'Controller.php');
InterfaceLoader::addItem('Model',          CMS_INTERFACE . 'Model.php');

/**
 * Staticne klase
 */
ClassLoader::addClass('ControllerLoader',CMS_STATIC . 'ControllerLoader.php');
ClassLoader::addClass('Lang',              CMS_STATIC . 'Lang.php');
ClassLoader::addClass('RegistryAdmin',    CMS_STATIC . 'RegistryAdmin.php');

/**
 * Abstraktne klase
 */
ClassLoader::addClass('CmsStart',           CMS_ABSTRACT . 'CmsStart.php',       'abstract');
ClassLoader::addClass('Controller',        CMS_ABSTRACT . 'controller.php',    'abstract', null, ['Controller']);
ClassLoader::addClass('Model',             CMS_ABSTRACT . 'Model.php',         'abstract', null, ['Model']);
ClassLoader::addClass('cms\lib\abstracts\View',             CMS_ABSTRACT . 'View.php',         'abstract');

/**
 * Public klase
 */
ClassLoader::addClass('Smarty',             CMS_SMARTY . 'Smarty.class.php',      'public');
ClassLoader::addClass('SmartyBC',           CMS_SMARTY . 'SmartyBC.class.php',    'public');

ClassLoader::addClass('cms\lib\publisher\View',              CMS_PUBLIC . 'View.php',             'public');

//ClassLoader::addClass('Cmindex',            CMS_MODEL . 'cmindex.php',            'public',        'Cmodel');
//ClassLoader::addClass('Ccindex',            CMS_CONTROLLER . 'ccindex.php',       'public',        'Controller');
//ClassLoader::addClass('Cvindex',            CMS_VIEW . 'cvindex.php',             'public',        'Ccview');

ClassLoader::addClass('cms\lib\mvc\model\ModelNews',             CMS_MODEL . 'ModelNews.php',             'public',        'Model');
ClassLoader::addClass('cms\lib\mvc\controller\ControllerNews',  CMS_CONTROLLER . 'ControllerNews.php','public',        'Controller');
//ClassLoader::addClass('Cvnews',             CMS_VIEW . 'cvnews.php',              'public',        'Ccview');

//ClassLoader::addClass('Cmadmin',            CMS_MODEL . 'cmadmin.php',            'public',        'Cmodel');
//ClassLoader::addClass('Ccadmin',            CMS_CONTROLLER . 'ccadmin.php',       'public',        'Controller');
//ClassLoader::addClass('Cvadmin',            CMS_VIEW . 'cvadmin.php',             'public',        'Ccview');

//Load staticnih klasa
ClassLoader::loadStaticClass();