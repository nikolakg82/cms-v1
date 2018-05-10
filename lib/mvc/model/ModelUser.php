<?php

namespace cms\lib\mvc\model;

use cms\CMS;
use cms\lib\abstracts\Model;

class ModelUser extends Model
{
    public function login($strEmail, $strPassword)
    {
        $mixLoginTokens = null;

        $strSql = "SELECT id FROM " . CMS::$dbPrefix . "users
                            WHERE email = :email AND password = :password AND active = 'y' AND confirmed_date > 0 LIMIT 1";

        $arrParams[':email'] = $strEmail;
        $arrParams[':password'] = md5($strPassword);
//print $strSql;
//        print $arrParams[':password'];
        CMS::$db->query($strSql, $arrParams);
        if(CMS::$db->rowCount() > 0)
        {
            $arrData = CMS::$db->fetch(FM_FETCH_ASSOC, false);
            CMS::$db->free();

            if(isset($arrData['id']))
            {
                $mixLoginTokens['token'] = md5($arrData['id'] . time());
                $mixLoginTokens['user'] = $arrData['id'];

                $strSqlUpdate = "UPDATE " . CMS::$dbPrefix . "users SET
                                    token = :token,
                                    token_expire_time = UNIX_TIMESTAMP() + 60 * 60 * 24
                                    ";

                $arrUpdateParams[':token'] = $mixLoginTokens['token'];
                CMS::$db->query($strSqlUpdate, $arrUpdateParams);

                if(CMS::$db->rowCount() == 0)
                    $mixLoginTokens = null;
            }
        }

        return $mixLoginTokens;
    }
}