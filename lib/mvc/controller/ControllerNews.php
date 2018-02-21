<?php

namespace cms\lib\mvc\controller;

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

    public function run()
    {
        self::$page = Request::name('page');

        if(!empty($this->getPath()))
        {
//            if(isset($this->get_path()[CMS::$db_prefix . 'news']))
//                $this->get_view()->one_news($this->get_path()[CMS::$db_prefix . 'news']);
//            else
//                $this->get_view()->list_news($this->get_path()[CMS::$db_prefix . 'news_category']);
        }
        else
        {
            $arrNews = $this->getModel()->listItems();

            $objResponse = $this->getResponse()->setData($arrNews)->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/list_news.tpl');
        }

        return $objResponse;
    }
}