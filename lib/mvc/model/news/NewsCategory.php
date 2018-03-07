<?php

namespace cms\lib\mvc\model\news;

use cms\CMS;
use cms\lib\abstracts\Model;

abstract class NewsCategory extends Model
{
    protected $table = "app_news_category";

    protected $mlc;

    protected $fields = array(
        'id'            => FM_AUTO,
        'code'          => FM_TEXT,
        'category_id'   => FM_NUMERIC,
        'picture'       => FM_TEXT,
        'date'          => FM_DATE_TIME,
        'pub_date'      => FM_DATE_TIME,
        'exp_date'      => FM_DATE_TIME,
        'ordinance'     => FM_NUMERIC,
        'active'        => FM_SWITCH . "|values:y:n"
    );

    public function __construct()
    {
//    var_dump(\app\lib\mvc\model\news\NewsCategoryMlc::class);
    var_dump(NewsCategoryMlc::class);
    die();
        $this->mlc = CMS::getModel(NewsCategoryMlc::class, CMS_C_NEWS);
    }
}