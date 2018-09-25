<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 2:16 PM
 */

namespace cms\lib\mvc\controller;

use cms\CMS;
use cms\lib\abstracts\Controller;
use fm\lib\help\Request;

class ControllerNews extends Controller
{
    /**
     * @var int - Number of news per page
     */
    public static $newsPage = 10;

    /**
     * @var int - Current page
     */
    public static $page;

    public function index($intPage = null, $intPerPage = null)
    {
        self::$page = $intPage;

        if(isset($intPerPage))
            self::$newsPage = $intPerPage;

        $arrNews = $this->getModel()->listItems();

        return $this->getResponse()->setData($arrNews)->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/list_news.tpl');
    }

    public function create()
    {
        return $this->getResponse()->setData("test")->setResponseCode(201)->setTemplatePath(CMS_C_NEWS . '/one_news.tpl');
    }

    public function categoryList()
    {
        $arrData = $this->getModel()->categoryList();

        return $this->getResponse()->setData($arrData)->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function categoryShow()
    {
        return $this->getResponse()->setData("categories Show")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function categoryCreate()
    {
        return $this->getResponse()->setData("category Create")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function categoryUpdate($intId)
    {
        return $this->getResponse()->setData("Category $intId Update")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function categoryDelete($intId)
    {
        return $this->getResponse()->setData("Category $intId delete")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function newsUpdate($intId)
    {
        return $this->getResponse()->setData("News $intId Update")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function newsDelete($intId)
    {
        return $this->getResponse()->setData("News $intId delete")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function run($strPath, $intPage = null, $intPerPage = null)
    {
        self::$page = $intPage;

        if(isset($intPerPage))
            self::$newsPage = $intPerPage;

        $objResponse = $this->getResponse()->setResponseCode(404)->setTemplatePath(CMS_C_STRUCTURE . '/404.tpl');

        if(isset($strPath))
        {
            if(!empty($this->getPath()))
            {
                if(isset($this->getPath()[CMS::$dbPrefix . 'news']))
                {
                    $arrData = $this->getModel()->oneItem($this->getPath()[CMS::$dbPrefix . 'news']);

                    if(isset($arrData))
                        $objResponse = $objResponse->setData($arrData)->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/one_news.tpl');
                }
                elseif(isset($this->getPath()[CMS::$dbPrefix . 'news_category']))
                {
                    $arrData = $this->getModel()->listItems($this->getPath()[CMS::$dbPrefix . 'news_category']);

                    if(isset($arrData))
                        $objResponse = $objResponse->setData($arrData)->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/list_news.tpl');
                }
            }
        }
        else
        {
            $arrNews = $this->getModel()->listItems();

            $objResponse = $objResponse->setData($arrNews)->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/list_news.tpl');
        }

        return $objResponse;
    }

    public function boxCategories()
    {
        $arrData = $this->getModel()->categoryList();

        $this->getResponse()->setData($arrData)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl')->showView();
    }

    public function boxLatestNews($intQuantity = 5)
    {
        $arrData = $this->getModel()->listItems(null, $intQuantity);

        $this->getResponse()->setData($arrData)->setTemplatePath(CMS_C_NEWS . '/box_news.tpl')->showView();
    }
}