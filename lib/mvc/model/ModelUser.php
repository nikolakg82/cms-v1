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
                $mixLoginTokens['token'] = $this->generateUserToken($arrData['id']);
                $mixLoginTokens['user'] = $arrData['id'];

                $strSqlUpdate = "UPDATE " . CMS::$dbPrefix . "users SET
                                    token = :token,
                                    token_expire_time = UNIX_TIMESTAMP() + 60 * 60 * 24
                                    WHERE id = :id
                                    ";

                $arrUpdateParams[':token'] = $mixLoginTokens['token'];
                $arrUpdateParams[':id'] = $mixLoginTokens['user'];
                CMS::$db->query($strSqlUpdate, $arrUpdateParams);

                if(CMS::$db->rowCount() == 0)
                    $mixLoginTokens = null;
            }
        }

        return $mixLoginTokens;
    }

    public function logout($strToken, $intUserId)
    {
        $boolReturn = true;

        $strSqlUpdate = "UPDATE " . CMS::$dbPrefix . "users SET
                                    token = '',
                                    token_expire_time = 0
                                    WHERE token = :token AND id = :id
                                    ";

        $arrUpdateParams[':token'] = $strToken;
        $arrUpdateParams[':id'] = $intUserId;
        CMS::$db->query($strSqlUpdate, $arrUpdateParams);

        if(CMS::$db->rowCount() == 0)
            $boolReturn = false;

        return $boolReturn;
    }

    public function generateUserToken($intUserId)
    {
        return md5($intUserId . time());
    }
}