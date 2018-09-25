<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 2:16 PM
 */

namespace cms\lib\mvc\model;

use cms\CMS;
use cms\lib\abstracts\Model;
use cms\lib\help\ControllerLoader;
use cms\lib\help\Lang;
use app\lib\mvc\controller\ControllerNews;
use fm\FM;

class ModelNews extends Model
{
    /**
     * Return list of news from category id, if category id null return all news, if news count null result is paginated.
     *
     * @param int $intCategoryId - Id category
     * @param int $intQuantity - News count
     * @return mixed
     */
    public function listItems($intCategoryId = null, $intQuantity = null)
    {
        $arrData = null;

        $strLink = "/" . ControllerLoader::getNameKeyLang(CMS_C_NEWS, Lang::getCurrent());

        $strSqlWhere = "";
        if(!empty($intCategoryId))
        {
            $strSqlWhere = " AND n.category_id = $intCategoryId";
            $arrData['category'] = $this->categoryList($intCategoryId);

            $strLink .= "/" . $arrData['category']['path'];
        }

        $strSqlLimit = "";
        if(!empty($intQuantity))
            $strSqlLimit = "LIMIT $intQuantity";
        else
        {
            $strSql = "SELECT COUNT(n.id) FROM " . CMS::$dbPrefix . "news n WHERE n.active = 'y'$strSqlWhere";
            CMS::$db->query($strSql);
            $intRecordData = CMS::$db->fetchCount();

            if(FM::is_variable($intRecordData))
            {
                $strLink .= "." . CMS::$view->getType() . "?";
                $arrData['pagination'] = $this->getPaginationData(ControllerNews::$page, ControllerNews::$newsPage, $intRecordData, $strLink);
                $strSqlLimit = $this->buildPaginationLimit(ControllerNews::$page, ControllerNews::$newsPage);
            }
        }

        $strSql = "SELECT n.id, n.code, m.path, n.date, m.title, m.text, m.picture
                    FROM " . CMS::$dbPrefix . "news n
                    LEFT JOIN " . CMS::$dbPrefix . "news_mlc m ON m.sid = n.id
                    WHERE n.active = 'y'$strSqlWhere AND m.lang = '" . Lang::getCurrent() . "'
                    ORDER BY n.ordinance ASC, n.date DESC, n.id DESC $strSqlLimit";

        CMS::$db->query($strSql);

        if(CMS::$db->rowCount() > 0)
            $arrData['news'] = CMS::$db->fetch();

        return $arrData;
    }

    /**
     * Return news from id, if id null return last news
     *
     * @param int $intItemId - Id news
     * @return mixed
     */
    public function oneItem($intItemId = null)
    {
        $arrData = null;
        $strSqlWhere = "";
        if(!empty($intItemId))
            $strSqlWhere = "AND n.id = $intItemId";

        $strSql = "SELECT n.id, n.code, m.path, n.date, n.category_id, m.title, m.text, m.picture
                    FROM " . CMS::$dbPrefix . "news n
                    LEFT JOIN " . CMS::$dbPrefix . "news_mlc m ON m.sid = n.id
                    WHERE n.active = 'y' $strSqlWhere AND m.lang = '" . Lang::getCurrent() . "'
                    ORDER BY n.ordinance ASC, n.date DESC, n.id DESC LIMIT 1";

        CMS::$db->query($strSql);
        if(CMS::$db->rowCount() > 0)
        {
            $arrData = CMS::$db->fetch(FM_FETCH_ASSOC, false);
            $arrData['category'] = $this->categoryList($arrData['category_id']);
        }

        return $arrData;
    }

    /**
     * Return category data, if id null return list all category
     *
     * @param int $intCategoryId - Id category
     * @return mixed
     */
    public function categoryList($intCategoryId = null)
    {
        $arrData = null;

        $strSqlWhere = "";
        if(!empty($intCategoryId))
            $strSqlWhere = "AND c.id = $intCategoryId";

        $strSql = "SELECT c.id, m.title, m.path, m.text FROM " . CMS::$dbPrefix . "news_category c
                  LEFT JOIN " . CMS::$dbPrefix . "news_category_mlc m ON m.sid = c.id
                  WHERE c.active = 'y' $strSqlWhere AND m.lang = '" . Lang::getCurrent() . "'
                  ORDER BY c.ordinance ASC, c.id ASC";

        CMS::$db->query($strSql);

        if(CMS::$db->rowCount() > 0)
        {
            if(!empty($intCategoryId))
                $arrData = CMS::$db->fetch(FM_FETCH_ASSOC, false);
            else
                $arrData = CMS::$db->fetch();
        }

        return $arrData;
    }
}