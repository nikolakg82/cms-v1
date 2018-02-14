<?php
/**
 * Created by PhpStorm.
 * User: Nikola
 * Date: 2/14/2018
 * Time: 1:27 PM
 */

interface Controller
{
    public function setModel();

    public function getModel();

    public function run();
}