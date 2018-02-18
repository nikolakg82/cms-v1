<?php

class Ccadmin extends Ccontroller
{
    public $user;

    public $func;

    public $action;

    public $show;

    public $id;

    public $sid;

    public $mlc_edit;

    public static $con;

    public static $item_page = 20;

    public static $table;

    public static $page;

    public static $controllers;

    public function run()
    {

    }

    public function work()
    {
        FM::start_session('APP_ADMIN', true);

        $this->func = Ffetch::name('func');
        self::$con = Ffetch::name('con');
        $this->action = Ffetch::name('action');
        $this->user = $this->get_model()->authorized_user();
        $this->show = Ffetch::name('show');

        self::$table = Ffetch::name('table');
        self::$page = Ffetch::name('page');
        $this->id = Ffetch::name('item_id');
        $this->sid = Ffetch::name('item_sid');
        $this->mlc_edit = Ffetch::name('mlc_edit');


        if(FM::is_variable($this->user))
        {
            if(FM::is_variable($this->func) && $this->func == 'logout')
                $this->get_model()->logout_user();

            CMS::$view->assign('user', $this->user);//Podaci korisnika
            CMS::$view->assign('chapter_data', RegistryAdmin::getChapters());//Raspodela tabela po kontrolerima i podela kotrolera

            if(FM::is_variable(self::$con))//Ako je setovan controler ide se na prikaz liste ili formi za editovanje i dodavanje, u suprotnom se prikazuje pocetna strana
            {
                CMS::$view->assign('con', self::$con);//Trenutni kontroler
                CMS::$view->assign('table', self::$table);//Trenutna tabela
                CMS::$view->assign('sid', $this->sid);//Sid

                if(FM::is_variable(self::$table))//Ako je setovana tabela moze nesto da se prikaze, a ako nije za sad nema nista, izbaciti poruku sa greskom
                {
                    $arrTableFull = FM::includer(APP_CONFIG_ADMIN . self::$table . '.php', false);//Konfiguracioni niz trenutne tabele
                    $arrTable = $arrTableFull[FM_FIELDS];//Konfiguracioni niz sa poljima trenutne tabele

                    if(FM::is_variable($arrTable))//Ako postoji konfiguracioni niz za tabelu moze da se ide dalje
                    {
                        CMS::$view->assign('table_data', $arrTable);//@TODO Ponavljanje table data, da li treba

                        if(FM::is_variable($this->action))//Ako je setovan action ide se sa akcijama, a ako nije prikazuje se lista rezultata
                        {
                            if($this->action == 'new' || $this->action == 'edit')
                            {
                                //@TODO dodavanje itema da se pretrese
                                //Ako se uspesno doda novi item ide prikaz za editovanje
                                    if(FM::is_variable($this->func) && $this->func == 'send' && $this->action == 'new')
                                    {
                                        $this->id = $this->get_model()->add_edit_item(self::$table, $arrTable);
                                        //@TODO ako je uspesno dodato izbaci poruku
                                        if(FM::is_variable($this->id))
                                        {
                                            $this->action = 'edit';
                                            $this->show = 'container';
                                        }
                                    }
                                //

                                if(FM::is_variable($this->show))//Ako je setovan show onda ide jedna vrsta prikaza u zavisnosti od toga sta je setovano
                                {

                                    if($this->show == 'container')//Ako je show container, onda se prikazuje template sa tabovima koji ima iframe i on uvlaci odgovarajuci sadrzaj
                                    {
                                        $arrContainerData[0]['title'] = "Item";
                                        $arrContainerData[0]['path'] = "/" . ControllerLoader::get_name_key_lang(CMS_C_ADMIN, Clang::get_current()) . ".html?con=" . self::$con . "&table=" . self::$table . "&action=" . $this->action . "&item_id=" . $this->id;

                                        if(isset($arrTableFull[FM_SUBTABLES]))
                                        {
                                            foreach($arrTableFull[FM_SUBTABLES] as $strTableChild)
                                            {
                                                $arrTableTemp = FM::includer(APP_CONFIG_ADMIN . $strTableChild . '.php', false);//Konfiguracioni niz pod tabele
                                                if(FM::is_variable($arrTableTemp))
                                                    $arrContainerData[] = array('title' => $arrTableTemp[FM_TITLE], 'path' => "/" . ControllerLoader::get_name_key_lang(CMS_C_ADMIN, Clang::get_current()) . ".html?con=" . self::$con . "&table=" . $strTableChild . "&item_sid=" . $this->id);
                                            }
                                        }

                                        CMS::$view->assign('container_data', $arrContainerData);

                                        CMS::$view->display(CMS::get_admin_theme() . CMS_C_ADMIN . '/container_edit.tpl');
                                    }
                                }
                                else//Ako nije setovan show onda se prikazuju forme za dodavanje/editovanje
                                {
                                    $arrFormItemData = $this->get_model()->get_full_form_data($arrTable);

                                    if($this->action == 'new')//Prikaz forme za dodavanje novog itema
                                    {
                                        $strFormAction = "/" . ControllerLoader::get_name_key_lang(CMS_C_ADMIN, Clang::get_current()) . ".html?con=" . self::$con . "&table=" . self::$table . "&action=new&func=send";//@TODO dinamicka putanja

                                        if(FM::is_variable($this->sid))
                                            $strFormAction .= "&item_sid=" . $this->sid;

                                        if(isset($arrTable['sid']) && FM::is_variable($this->sid))
                                            $arrFormItemData['sid']['value'] = $this->sid;

                                    }
                                    elseif($this->action == 'edit' && FM::is_variable($this->id))
                                    {
                                        //@TODO ovaj if da se pretrese
                                        if(FM::is_variable($this->func) && $this->func == 'send')
                                        {
                                            if(FM::is_variable($this->mlc_edit) && $this->mlc_edit == 'ok')
                                            {
                                                $intMlcItemId = Ffetch::post('id');
                                                if(FM::is_variable($intMlcItemId))
                                                {
                                                    $this->get_model()->add_edit_item(self::$controllers[self::$con]['tables'][self::$table]['mlc'], FM::includer(APP_CONFIG_ADMIN . self::$controllers[self::$con]['tables'][self::$table]['mlc'] . ".php", false), $intMlcItemId);
                                                    //@TODO ako je uspesno dodato izbaci poruku
                                                }
                                                else
                                                {
                                                    $intMlcItemId = $this->get_model()->add_edit_item(self::$controllers[self::$con]['tables'][self::$table]['mlc'], FM::includer(APP_CONFIG_ADMIN . self::$controllers[self::$con]['tables'][self::$table]['mlc'] . ".php", false));
                                                    //@TODO ako je uspesno dodato izbaci poruku
                                                }
                                            }
                                            else
                                            {
                                                $this->get_model()->add_edit_item(self::$table, $arrTable, $this->id);
                                                //@TODO ako je uspesno dodato izbaci poruku
                                            }

                                            //@TODO Kad se nesto edituje traba uraditi rebild putanja
                                        }

                                        //MLC forme mogu da se prikazu samo ako je item vec dodat i ako se edituje
                                            if(isset($arrTableFull[FM_MLC]))
                                            {
                                                $arrMlcTable = FM::includer(APP_CONFIG_ADMIN . $arrTableFull[FM_MLC] . ".php", false);
                                                $arrMlcData = $this->get_model()->list_data($arrMlcTable[FM_FIELDS], $arrTableFull[FM_MLC], false, $this->id);
                                                $arrMlcTable = $this->get_model()->reorganize_mlc_result($arrMlcTable[FM_FIELDS], $arrMlcData, $this->id);

                                                CMS::$view->assign('mlc_data', $arrMlcTable);
                                            }
                                        //
                                        $arrFormItemData = $this->get_model()->get_item_data(self::$table, $arrFormItemData, $this->id);

                                        $strFormAction = "/" . ControllerLoader::get_name_key_lang(CMS_C_ADMIN, Clang::get_current()) . ".html?con=" . self::$con . "&table=" . self::$table . "&action=edit&func=send&item_id=" . $this->id;//@TODO dinamicka putanja
                                        if(FM::is_variable($this->sid))
                                            $strFormAction .= "&item_sid=" . $this->sid;
                                    }

                                    //Action forme
                                    CMS::$view->assign('form_action', $strFormAction);
                                    //Podaci potrebni za bildovanje forme za jedan item, sa unetim vrednostim ako ih ima
                                    CMS::$view->assign('form_item_data', $arrFormItemData);
                                    //Prikaz form item
                                    CMS::$view->display(CMS::get_admin_theme() . CMS_C_ADMIN . '/form_item.tpl');
                                }
                            }
                            else
                            {
                                //Za sad nema nista
                            }

                        }
                        else //Prikazuje se lista rezultata za tabelu
                        {
                            $this->get_view()->list_data($arrTable, self::$table, true, $this->sid);
                        }
                    }
                }
                else
                {
                    // @TODO Treba da se izbaci greska zabranjen pristup
                }

            }
            else
            {
                $this->get_view()->index();
            }
        }
        else
        {
            if(FM::is_variable($this->func) && $this->func == 'submit')
            {
                if(!$this->get_model()->login_user(Ffetch::post('username'), Ffetch::post('password')))
                    CMS::$view->assign('message', "Pogresni podaci");

            }

            $this->get_view()->login_form();
        }
    }
}