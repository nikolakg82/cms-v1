<?php

class CregistryAdmin
{
    /**
     * @var - Poglavlja sa kontrolerima koji su aktivni
     *
     * Tip niza je array(
     *      'Naziv chaptera je i kljuc niza' => array(
     *              'icon'        => 'Naziv ikonice',
     *              'controllers' => array(
     *                    'Kluc kontrolera' => array(
     *                                      'title' => 'Prikazani naslov',
     *                                      'icon'  => 'Ikonica',
     *                                      'tables'=> array(
     *                                                      'Naziv tabele' => array(
     *                                                                           'title' => 'Prikazani naslov',
     *                                                                           'icon'  => 'Ikonica',
     *                                                                           ),
     *                                                      ),
     *                                  ),
     *                     ),
     *          ),
     *      );
     *
     */
    private static $chapters;

    public static function add_controller($strChapter, $strChapterIcon, $strController, $arrData)
    {
        self::$chapters[$strChapter]['icon'] = $strChapterIcon;
        self::$chapters[$strChapter]['controllers'][$strController] = $arrData;
    }

    public static function get_chapters()
    {
        return self::$chapters;
    }

//    public static function get_table_config($strController, $strTable)
//    {
//        if(FM::is_variable(self::$controllers[$strController]['tables'][$strTable]))
//            return FM::includer(APP_CONFIG_ADMIN . "")
//    }
}