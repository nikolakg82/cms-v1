<?php

/**
 * Add interfaces
 */
 InterfaceLoader::addItem('Controller',     CMS_INTERFACE . 'Controller.php');

/**
 * Staticne klase
 */
Floader::add_class('CregistryController',CMS_STATIC . 'cregistrycontroller.php');
Floader::add_class('Clang',              CMS_STATIC . 'clang.php');
Floader::add_class('CCregistryAdmin',    CMS_STATIC . 'cregistryadmin.php');

/**
 * Abstraktne klase
 */
Floader::add_class('Cstart',             CMS_ABSTRACT . 'cstart.php',         'abstract');
Floader::add_class('Ccontroller',        CMS_ABSTRACT . 'ccontroller.php',    'abstract', null, ['Controller']);
Floader::add_class('Cmodel',             CMS_ABSTRACT . 'cmodel.php',         'abstract');
Floader::add_class('Ccview',             CMS_ABSTRACT . 'ccview.php',         'abstract');

/**
 * Public klase
 */
Floader::add_class('Smarty',             CMS_SMARTY . 'Smarty.class.php',      'public');
Floader::add_class('SmartyBC',           CMS_SMARTY . 'SmartyBC.class.php',    'public');

Floader::add_class('Cview',              CMS_PUBLIC . 'cview.php',             'public');

Floader::add_class('Cmindex',            CMS_MODEL . 'cmindex.php',            'public',        'Cmodel');
Floader::add_class('Ccindex',            CMS_CONTROLLER . 'ccindex.php',       'public',        'Ccontroller');
Floader::add_class('Cvindex',            CMS_VIEW . 'cvindex.php',             'public',        'Ccview');

Floader::add_class('Cmnews',             CMS_MODEL . 'cmnews.php',             'public',        'Cmodel');
Floader::add_class('Ccnews',             CMS_CONTROLLER . 'ccnews.php',        'public',        'Ccontroller');
Floader::add_class('Cvnews',             CMS_VIEW . 'cvnews.php',              'public',        'Ccview');

Floader::add_class('Cmadmin',            CMS_MODEL . 'cmadmin.php',            'public',        'Cmodel');
Floader::add_class('Ccadmin',            CMS_CONTROLLER . 'ccadmin.php',       'public',        'Ccontroller');
Floader::add_class('Cvadmin',            CMS_VIEW . 'cvadmin.php',             'public',        'Ccview');

//Load staticnih klasa
Floader::load_static_class();