<?php

/**
 * Created by PhpStorm.
 * User: Nikola
 * Date: 2/16/2018
 * Time: 1:58 PM
 */

namespace cms\lib\interfaces;

interface Model
{
    public function getPaginationData($intPage, $intPaginationNumber, $intRecCount, $strLink = "", $intShowPage = 3);

    public function buildPaginationLimit($intPage, $intItemForPage);
}