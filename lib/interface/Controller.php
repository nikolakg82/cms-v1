<?php
/**
 * Created by PhpStorm.
 * User: Nikola
 * Date: 2/16/2018
 * Time: 2:51 PM
 */
namespace cms\lib\interfaces;

interface Controller
{
    public function setModel(Model $objModel);

    public function getModel();

    public function run();
}