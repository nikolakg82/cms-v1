<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 2/16/2018
 * Time: 1:58 PM
 */

namespace cms\lib\interfaces;

interface Model
{
    /**
     * Get data for pagination result
     *
     * @param $intPage - Current page
     * @param $intPaginationNumber - Item for page
     * @param $intRecCount - Total items
     * @param string $strLink - Link to the page
     * @param int $intShowPage - Show page links to pagination
     * @return mixed
     */
    public function getPaginationData($intPage, $intPaginationNumber, $intRecCount, $strLink = "", $intShowPage = 3);

    /**
     * Build mysql limit for pagination result
     *
     * @param $intPage - Current page
     * @param $intItemForPage - Item for page
     * @return string
     */
    public function buildPaginationLimit($intPage, $intItemForPage);
}