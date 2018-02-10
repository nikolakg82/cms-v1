<?php

class Ccnews extends Ccontroller
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
        self::$page = Ffetch::name('page');

        if(FM::is_variable($this->get_path()))
        {
            if(FM::is_variable($this->get_path()[CMS::$db_prefix . 'news']))
                $this->get_view()->one_news($this->get_path()[CMS::$db_prefix . 'news']);
            else
                $this->get_view()->list_news($this->get_path()[CMS::$db_prefix . 'news_category']);
        }
        else
            $this->get_view()->list_news();
    }
}