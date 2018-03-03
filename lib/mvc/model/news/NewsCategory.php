<?php
/**
 * Created by PhpStorm.
 * User: IMS-WS01
 * Date: 3/3/2018
 * Time: 8:52 PM
 */

namespace lib\mvc\model\news;

use cms\lib\abstracts\Model;

class NewsCategory extends Model
{
    protected $table = "app_news_category";

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
}