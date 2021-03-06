<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 3:17 PM
 */

namespace cms\lib\abstracts;

use fm\lib\help\Numeric;

abstract class Model implements \cms\lib\interfaces\Model
{
    public function getPaginationData($intPage, $intPaginationNumber, $intRecCount, $strLink = "", $intShowPage = 3)
    {
        if(empty($intPage))
            $intPage = 1;

        $nextPage = $intPage+1;
        $previousPage = $intPage - 1;
        $lastPage = Numeric::convertToInteger(Numeric::ceil($intRecCount / $intPaginationNumber));
        $arrData['active'] = $intPage;
        $arrData['first'] = "$strLink" . "page=1";
        $arrData['last'] = "$strLink" . "page=$lastPage";
        $arrData['previous'] = "$strLink" . "page=$previousPage";
        $arrData['next'] = "$strLink" . "page=$nextPage";
        $arrData['count'] = $lastPage;
        $arrData['endPoint'] = true;
        $arrData['beginPoint'] = true;
        if ($intPage == 1)
        {
            $arrData['previous'] = null;
            $arrData['first'] = null;
        }

        if ($intPage == $lastPage)
        {
            $arrData['next'] = null;
            $arrData['last'] = null;
        }
        $intFirstPageShow = $intPage - $intShowPage;
        $intLastPageShow = $intPage + $intShowPage;

        if ($intPage + $intShowPage >= $lastPage)
        {
            $arrData['endPoint'] = false;
            $intLastPageShow = $lastPage;
        }
        if ($intPage - $intShowPage <= 1)
        {
            $arrData['beginPoint'] = false;
            $intFirstPageShow = 1;
        }

        for ($page = $intFirstPageShow; $page <= $intLastPageShow; $page++)
        {
            $pageName = $page;
            $pageLink = "$strLink" . "page=$page";
            if ($page == $intPage)
            {
                $pageLink = null;
            }

            $arrData['page'][$pageName] = $pageLink;
        }

        return $arrData;
    }

    public function buildPaginationLimit($intPage, $intItemForPage)
    {
        if(empty($intPage))
            $intPage = 1;

        return "LIMIT " . (($intPage - 1) * $intItemForPage ) . ", $intItemForPage";
    }
}