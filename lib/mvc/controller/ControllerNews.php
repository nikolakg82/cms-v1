<?php

namespace cms\lib\mvc\controller;

use cms\lib\abstracts as abstracts;
use fm\lib\help as help;

class ControllerNews extends abstracts\Controller
{
    /**
     * @var int - Broj vesti po strani
     */
    public static $news_page = 10;

    /**
     * @var int - Trenutna strana
     */
    public static $page;

    public function run()
    {
        self::$page = help\Ffetch::name('page');

        $objResponse = help\ClassLoader::load("Response");

        if(FM::is_variable($this->get_path()))
        {
//            if(isset($this->get_path()[CMS::$db_prefix . 'news']))
//                $this->get_view()->one_news($this->get_path()[CMS::$db_prefix . 'news']);
//            else
//                $this->get_view()->list_news($this->get_path()[CMS::$db_prefix . 'news_category']);
        }
        else
        {

            $arrNews = $this->getModel()->list_items();

            $objResponse = $objResponse->setData($arrNews)->setResponseCode(200)->setTemplatePath(CMS_C_NEWS . '/list_news.tpl');

//            return ['data' => $arrNews, 'template' => CMS_C_NEWS . '/list_news.tpl'];
//            var_dump($arrNews);
//            $this->get_view()->list_news();
        }

        return $objResponse;
    }
}