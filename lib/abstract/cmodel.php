<?php

abstract class Cmodel implements \cms\lib\interfaces\Model
{
    public function getPaginationData()
    {
        // TODO: Implement getPaginationData() method.
    }

    public function buildPaginationLimit()
    {
        // TODO: Implement buildPaginationLimit() method.
    }

    public function pagination_data($intPage, $intPaginationNumber, $intRecCount, $strLink = "", $intShowPage = 3)
    {
        if(!(FM::is_variable($intPage)))
            $intPage = 1;

        $nextPage = $intPage+1;
        $previousPage = $intPage - 1;
        $lastPage = Finteger::convert_to_integer(Finteger::ceil($intRecCount / $intPaginationNumber));
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

    public function bild_limit_pagination($intPage, $intItemForPage)
    {
        if(!(FM::is_variable($intPage)))
            $intPage = 1;

        return "LIMIT " . (($intPage - 1) * $intItemForPage ) . ", $intItemForPage";
    }
}