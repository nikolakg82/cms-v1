<?php

namespace cms\lib\mvc\model\news;

use cms\lib\abstracts\Model;

abstract class NewsCategoryMlc extends Model
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