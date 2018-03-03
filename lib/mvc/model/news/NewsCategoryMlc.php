<?php
/**
 * Created by PhpStorm.
 * User: IMS-WS01
 * Date: 3/3/2018
 * Time: 8:52 PM
 */

namespace lib\mvc\model\news;

use cms\lib\abstracts\Model;

class NewsCategoryMlc extends Model
{
    protected $table = 'app_news_category_mlc';

    protected $fields = array(
        'id'        => FM_AUTO,
        'lang'      => FM_TEXT . '|length:2',
        'sid'       => FM_NUMERIC,
        'path'      => FM_TEXT,
        'title'     => FM_TEXT_AREA,
        'text'      => FM_TEXT_EDITOR,
        'picture'   => FM_TEXT
    );
}