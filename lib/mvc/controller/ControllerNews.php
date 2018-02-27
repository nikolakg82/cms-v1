<?php

namespace cms\lib\mvc\controller;

use cms\CMS;
use cms\lib\abstracts\Controller;
use fm\lib\help\Request;

class ControllerNews extends Controller
{
    /**
     * @var int - Broj vesti po strani
     */
    public static $newsPage = 10;

    /**
     * @var int - Trenutna strana
     */
    public static $page;

    public function index()
    {
        self::$page = Request::name('page');

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

    public function categoryUpdate()
    {
        return $this->getResponse()->setData("Category Update")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function categoryDelete()
    {
        return $this->getResponse()->setData("Category delete")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function newsUpdate()
    {
        return $this->getResponse()->setData("News Update")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function newsDelete()
    {
        return $this->getResponse()->setData("News delete")->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/box_category_news.tpl');
    }

    public function run()
    {
        self::$page = Request::name('page');
        $strPath = Request::get('path');

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