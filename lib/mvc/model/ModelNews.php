<?php

namespace cms\lib\mvc\model;

use cms\CMS;
use cms\lib\abstracts\Model;
use cms\lib\help\ControllerLoader;
use cms\lib\help\Lang;
use cms\lib\mvc\controller\ControllerNews;
use fm\FM;

class ModelNews extends Model
{
    /**
     * Vraca niz sa vestima, ako je prosledjena kategorija, vraca podatke o samoj kategoriji i listu vesti za tu kategoriju, ako je prosledjena kolicina vraca taj broj vesti,
     * a ako nije postranicava vesti i vraca niz sa potrebnim podacima za paginaciju
     *
     * @param int $intCategoryId - id kategorije
     * @param int $intQuantity - Broj vesti
     * @return mixed
     */
    public function list_items($intCategoryId = null, $intQuantity = null)
    {
        $arrData = null;

        $strLink = "/" . ControllerLoader::getNameKeyLang(CMS_C_NEWS, Lang::getCurrent());

        $strSqlWhere = "";
        if(FM::is_variable($intCategoryId))
        {
            $strSqlWhere = " AND n.category_id = $intCategoryId";
            $arrData['category'] = $this->category_list($intCategoryId);

            $strLink .= "/" . $arrData['category']['path'];
        }

        $strSqlLimit = "";
        if(FM::is_variable($intQuantity))
            $strSqlLimit = "LIMIT $intQuantity";
        else
        {
            $strSql = "SELECT COUNT(n.id) FROM " . CMS::$dbPrefix . "news n WHERE n.active = 'y'$strSqlWhere";
            CMS::$db->query($strSql);
            $intRecordData = CMS::$db->fetchCount();

            if(FM::is_variable($intRecordData))
            {
                $strLink .= "." . CMS::$view->getType() . "?";
                $arrData['pagination'] = $this->getPaginationData(ControllerNews::$page, ControllerNews::$news_page, $intRecordData, $strLink);
                $strSqlLimit = $this->buildPaginationLimit(ControllerNews::$page, ControllerNews::$news_page);
            }
        }

        $strSql = "SELECT n.id, n.code, m.path, n.date, m.title, m.text, m.picture
                    FROM " . CMS::$db_prefix . "news n
                    LEFT JOIN " . CMS::$db_prefix . "news_mlc m ON m.sid = n.id
                    WHERE n.active = 'y'$strSqlWhere AND m.lang = '" . Clang::get_current() . "'
                    ORDER BY n.ordinance ASC, n.date DESC, n.id DESC $strSqlLimit";

        CMS::$db->query($strSql);

        if(CMS::$db->rowCount() > 0)
            $arrData['news'] = CMS::$db->fetch();

        return $arrData;
    }

    /**
     * Vraca podatke za jednu vest, ako je prosledjen id za tu vest, ako nije vraca poslednju vest
     *
     * @param int $intItemId - id vesti
     * @return mixed
     */
    public function one_item($intItemId = null)
    {
        $arrData = null;
        $strSqlWhere = "";
        if(FM::is_variable($intItemId))
            $strSqlWhere = "AND n.id = $intItemId";

        $strSql = "SELECT n.id, n.code, m.path, n.date, n.category_id, m.title, m.text, m.picture
                    FROM " . CMS::$db_prefix . "news n
                    LEFT JOIN " . CMS::$db_prefix . "news_mlc m ON m.sid = n.id
                    WHERE n.active = 'y' $strSqlWhere AND m.lang = '" . Clang::get_current() . "'
                    ORDER BY n.ordinance ASC, n.date DESC, n.id DESC LIMIT 1";

        CMS::$db->query($strSql);
        if(CMS::$db->row_count() > 0)
        {
            $arrData = CMS::$db->fetch(FM_FETCH_ASSOC, false);
            $arrData['category'] = $this->category_list($arrData['category_id']);
        }

        return $arrData;
    }

    /**
     * Vraca listu svih kategorija vesti, ako je prosledjen id vraca samo tu kategoriju
     *
     * @param int $intCategoryId - id kategorije
     * @return mixed
     */
    public function category_list($intCategoryId = null)
    {
        $arrData = null;

        $strSqlWhere = "";
        if(FM::is_variable($intCategoryId))
            $strSqlWhere = "AND c.id = $intCategoryId";

        $strSql = "SELECT c.id, m.title, m.path, m.text FROM " . CMS::$db_prefix . "news_category c
                  LEFT JOIN " . CMS::$db_prefix . "news_category_mlc m ON m.sid = c.id
                  WHERE c.active = 'y' $strSqlWhere AND m.lang = '" . Clang::get_current() . "'
                  ORDER BY c.ordinance ASC, c.id ASC";

        CMS::$db->query($strSql);

        if(CMS::$db->row_count() > 0)
        {
            if(FM::is_variable($intCategoryId))
                $arrData = CMS::$db->fetch(FM_FETCH_ASSOC, false);
            else
                $arrData = CMS::$db->fetch();
        }

        return $arrData;
    }
}