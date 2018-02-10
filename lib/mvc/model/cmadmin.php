<?php

class Cmadmin extends Cmodel
{
    /**
     * @var array - Podaci ze default korisnika
     */
    protected $default_user = array(
                                    'username'  => 'test',
                                    'name'      => 'Super admin',
                                    'permission'=> 1
                                    );

    /**
     * @var string - Sifra za default korisnika, tj. md5 od sifre
     */
    protected $password = 'e10adc3949ba59abbe56e057f20f883e';//123456

    /**
     * Autorizacija korisnika, ako postoji sesija vraca podatke korisnika
     *
     * @return array - podaci korisnika
     */
    public function authorized_user()
    {
        return Farray::unserialize(Ffetch::session('APP_ADMIN_USER'));
    }

    /**
     * Logovanje korisnika, prvo se proverava default korisnik, ako se podaci ne poklapaju ide se sa loginom iz baze, vraca false ako login nije uspe a ako jeste radi redirekt
     *
     * @param $strUsername - Username
     * @param $strPassword - Sifra
     * @return bool - status
     */
    public function login_user($strUsername, $strPassword)
    {
        $arrUserData = null;

        if($strUsername == $this->default_user['username'] && Fstring::md5($strPassword) == $this->password)
        {
            $arrUserData = $this->default_user;

        }
        //@TODO napravi dinamicki login iz baze

        if(FM::is_variable($arrUserData))
        {
            FM::start_session('APP_ADMIN');
            FM::set_session_data('APP_ADMIN_USER', Farray::serialize($arrUserData));

            FM::redirect("/" . CregistryController::get_name_key_lang(CMS_C_ADMIN, Clang::get_current()) . ".html");
        }

        return false;
    }

    /**
     * Logout korisnika, ubija se sesija i radi se redirekt
     */
    public function logout_user()
    {
        FM::kill_session('APP_ADMIN_USER', 'APP_ADMIN');
        FM::redirect("/" . CregistryController::get_name_key_lang(CMS_C_ADMIN, Clang::get_current()) . ".html");
    }

    /**
     * Za prosledjenu tabelu i konfiguracioni niz tabele vraca unete podatke, ako je prosledjen sid radi filtriranje prema sid-u, ako je $boolPagination true radi postranicavanje
     *
     * @param $arrTableData - Konfiguracioni niz tabele
     * @param $strTable - Naziv tabele
     * @param bool $boolPagination - Paginacija da ili ne
     * @param null $intSid - Sid
     * @return mixed
     */
    public function list_data($arrTableData, $strTable, $boolPagination = true, $intSid = null)
    {
        $arrData = null;

        $strSqlSelect = "";
        $strSqlJoin = "";
        $strSqlLimit = "";
        $strSqlWhere = "";
        if(FM::is_variable($arrTableData))
        {

            if($boolPagination)
            {
                $strLink = "/" . CregistryController::get_name_key_lang(CMS_C_ADMIN, Clang::get_current()) . ".html?con=" . Cadmin::$con . "&table=" . $strTable;
                $strSql = "SELECT COUNT(id) FROM " . Cadmin::$table;
                CMS::$db->query($strSql);
                $intRecordData = CMS::$db->fetch_count();

                if(FM::is_variable($intRecordData))
                {
                    $strLink .= "&";
                    $arrData['pagination'] = $this->pagination_data(Cadmin::$page, Cadmin::$item_page, $intRecordData, $strLink);
                    $strSqlLimit = $this->bild_limit_pagination(Cadmin::$page, Cadmin::$item_page);
                }
            }

            //Zbog dupliranja naziva tabel u join ide se sad dinamiskim aliasima za tabele
            $strTableAlias = "my_table";
            $strJoinTableAlias = "my_join_table_";
            $intJoinTableAliasCounter = 1;
//            var_dump($arrTableData);
            foreach($arrTableData as $strField => $arrField)
            {
//                if($arrField[CMS_T_TYPE] != FM_MLC)
//                {
                    if(isset($arrField['join']))
                    {
                        $strSqlJoin .= "LEFT JOIN " . $arrField['join']['table'] . " AS " . $strJoinTableAlias . $intJoinTableAliasCounter;
                         if(isset($arrField['join']['where_join']))
                         {
                             $strSqlJoin .= " ON (";
                             foreach($arrField['join']['where_join'] as $strKeyJoin => $strValJoin)
                                 $strSqlJoin .= $strJoinTableAlias . $intJoinTableAliasCounter . "." . $strKeyJoin . " = " . $strValJoin . " AND ";

                             $strSqlJoin = Fstring::substr($strSqlJoin, 0, -5);

                             $strSqlJoin .= ") ";
                         }

                        $strSqlSelect .= $strJoinTableAlias . $intJoinTableAliasCounter . "." . $arrField['join']['field'] . " AS $strField,";

                        if(isset($arrField['join']['where']))
                        {
                            foreach($arrField['join']['where'] as $strKeyWhere => $strValWhere)
                                $strSqlWhere .= $strJoinTableAlias . $intJoinTableAliasCounter . "." . $strKeyWhere . " = " . $strValWhere . " AND ";
                        }

                        $intJoinTableAliasCounter++;
                    }
                    else
                        $strSqlSelect .= $strTableAlias . "." . $strField . ",";
//                }
            }

            $strSqlSelect = Fstring::substr($strSqlSelect, 0, -1);

            $strSql = "SELECT $strSqlSelect FROM " . $strTable . " AS $strTableAlias $strSqlJoin";

            if(FM::is_variable($intSid))
            {
                $strSqlWhere .= "$strTableAlias.sid = $intSid AND ";
            }

            if(FM::is_variable($strSqlWhere))
            {
                $strSqlWhere = Fstring::substr($strSqlWhere, 0, -5);
                $strSqlWhere = "WHERE $strSqlWhere";
            }

            $strSql .= " $strSqlWhere ORDER BY $strTableAlias.id DESC $strSqlLimit";

            CMS::$db->query($strSql);

            if(CMS::$db->row_count() > 0)
                $arrData['data'] = CMS::$db->fetch();

        }

        return $arrData;
    }

    /**
     * Ako imamo polje select cije su opcije vrednosti neke druge tabele onda ova funkcija vraca te vrednosti, npr. kategorije
     *
     * @param $arrDataTable konfiguracioni niz tabele
     * @return mixed
     */
    public function get_full_form_data($arrDataTable)
    {
        foreach($arrDataTable as &$val)
        {
            if($val['type'] == FM_SELECT && !isset($val['values']) && isset($val['join']))
                $val['values'] = $this->get_data_table($val['join']['key'], $val['join']['field'], $val['join']['table'], $val['join']['where']);
        }

        return $arrDataTable;
    }

    /**
     * Vraca niz sa podacima koji su potrebni da popune ocije select taga u formi
     *
     * @param $strKey - Kljuc, uglavnom je to id
     * @param $strField - Naziv ili title
     * @param $strTable - Tabela
     * @param $arrWhere - Niz sa uslovima za upit
     * @return array
     */
    public function get_data_table($strKey, $strField, $strTable, $arrWhere)
    {
        $arrData = null;

        $strSql = "SELECT $strKey AS id, $strField AS title FROM  $strTable";

        if(FM::is_variable($arrWhere))
        {
            $strSql .= " WHERE ";
            foreach($arrWhere as $key => $val)
                $strSql .= $key . " = " . $val . " AND ";

            $strSql = Fstring::substr($strSql, 0, -5);
        }

        CMS::$db->query($strSql);

        if(CMS::$db->row_count() > 0)
            $arrData = CMS::$db->fetch();

        return $arrData;
    }

    public function add_edit_item($strTable, $arrTable, $intElementId = null)
    {
        $strSql = '';
        $arrPrepare = null;
        foreach($arrTable as $key => $val)
        {
            if($val[CMS_T_TYPE] == FM_TEXT || $val[CMS_T_TYPE] == FM_SELECT || $val[CMS_T_TYPE] == FM_DATE || $val[CMS_T_TYPE] == FM_NUMERIC || $val[CMS_T_TYPE] == FM_SWITCH
                || $val[CMS_T_TYPE] == FM_TEXT_AREA || $val[CMS_T_TYPE] == FM_TEXT_EDITOR)
            {
                $strVal = Ffetch::name($key);

                // @TODO Ovo bi mozda trebalo malo bolje
                if($val[CMS_T_TYPE] == FM_SWITCH)
                {
                    if(isset($strVal))
                        $strVal = 'y';
                    else
                        $strVal == 'n';
                }
                $strSql .= $key . " = :$key,";

                $arrPrepare[":$key"] = $strVal;
            }
        }

        $strSql = Fstring::substr($strSql, 0, -1);


        if(FM::is_variable($strSql))
        {
            if(FM::is_variable($intElementId))
            {
                $arrPrepare[":id"] = $intElementId;
                $strSql = "UPDATE $strTable SET $strSql WHERE id = :id";

                CMS::$db->query($strSql, $arrPrepare);
            }
            else
            {
                $strSql = "INSERT INTO $strTable SET $strSql";

                CMS::$db->query($strSql, $arrPrepare);

                if(CMS::$db->row_count() > 0)
                    $intElementId = CMS::$db->last_insert_id();
            }
        }

        return $intElementId;
    }

    /**
     * Za prosledjenu tabelu, id i konfiguracionog niza vraca unete vrednosti za taj id, vrednosti dodaje u konfiguracioni niz od tabele
     *
     * @param $strTable - Naziv tabele
     * @param $arrTable - knfiguracioni niz tabele
     * @param $intElementId - Id
     * @return array
     */
    public function get_item_data($strTable, $arrTable, $intElementId)
    {
        $strSql = "";
        foreach($arrTable as $key => $val)
        {
            if($val[CMS_T_TYPE] == FM_TEXT || $val[CMS_T_TYPE] == FM_NUMERIC || $val[CMS_T_TYPE] == FM_SELECT || $val[CMS_T_TYPE] == FM_DATE || $val[CMS_T_TYPE] == FM_SWITCH || $val[CMS_T_TYPE] == FM_AUTO)
                $strSql .= $key . ",";
        }

        if(FM::is_variable($strSql))
        {
            $strSql = Fstring::substr($strSql, 0, -1);

            $strSql = "SELECT $strSql FROM $strTable";

            if(FM::is_variable($intElementId))
                $strSql .= " WHERE id = $intElementId";

            CMS::$db->query($strSql);

            if(CMS::$db->row_count() > 0)
            {
                $arrData = CMS::$db->fetch(FM_FETCH_ASSOC, false);

                if(FM::is_variable($arrData))
                {
                    foreach($arrData as $keyData => $valData)
                    {
                        if(isset($arrTable[$keyData]))
                            $arrTable[$keyData]['value'] = $valData;

                    }
                }
            }
        }

        return $arrTable;
    }

    public function reorganize_mlc_result($arrTableData, $arrResult, $intSid = null)
    {
        $arrReturn = null;

        foreach(Clang::get_lang() as $key => $val)
        {
            $arrReturn[$key] = $arrTableData;

            if(FM::is_variable($arrResult['data']))
            {
                foreach($arrResult['data'] as $arrVal)
                {
                    if($arrVal['lang'] == $key)
                    {
                        foreach($arrReturn[$key] as $keyField => $valField)
                        {
                            $arrReturn[$key][$keyField]['value'] = $arrVal[$keyField];
                        }
                    }
                }
            }


            if(!FM::is_variable($arrReturn[$key]['lang']['value']))
                $arrReturn[$key]['lang']['value'] = $key;

            if(!FM::is_variable($arrReturn[$key]['sid']['value']))
                $arrReturn[$key]['sid']['value'] = $intSid;
        }

        return $arrReturn;
    }
}