<?php

class Cvnews extends Ccview
{
    /**
     * Prikazuje jednu vest
     *
     * @param $intNewsId - Id vesti
     */
    public function one_news($intNewsId)
    {
        $arrData = $this->get_model()->one_item($intNewsId);

        CMS::$view->assign('data', $arrData);
        CMS::$view->display(CMS_C_NEWS . '/one_news.tpl');
    }

    /**
     * Prikazuje listu vesti, za prosledjenu kategoriju i kolicinu, ako kategorije nije prosledjena lista sve vesti, a ako kolicina nije prosledjena onda prikazuje paginaciju
     *
     * @param null $intCategoryId - id kategorija
     * @param null $intQuantity - broj vesti za prikaz
     */
    public function list_news($intCategoryId = null, $intQuantity = null)
    {
        $arrData = $this->get_model()->list_items($intCategoryId, $intQuantity);

        if(isset($arrData['news']))
        {
            CMS::$view->assign('data', $arrData);
            CMS::$view->display(CMS_C_NEWS . '/list_news.tpl');
        }
        else
        {
            CMS::$view->assign('message', "Nema vesti");
            CMS::$view->display(CMS_C_STRUCTURE . '/info.tpl');
        }
    }

    //Funkcije koje rade show_data, odmah rade prikaz, najprakticnije su da se pozivaju iz smarty-ja
    /**
     * Prikazuje poslednje vesti u boxu (leva, desna kolona)
     *
     * @param int $intQuantity - Broj vesti za prikaz
     */
    public function box_news($intQuantity = 4)
    {
        CMS::$view->show_data(CMS_C_NEWS . '/box_news.tpl', $this->get_model()->list_items(null, $intQuantity));
    }

    /**
     * Lista i prikazuje kategorije u boxu (leva, desna kolona)
     */
    public function box_category()
    {
        CMS::$view->show_data(CMS_C_NEWS . '/box_category_news.tpl', $this->get_model()->category_list());
    }
}