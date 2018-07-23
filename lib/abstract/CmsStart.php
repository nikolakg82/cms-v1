<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 5/3/2016
 * Time: 3:17 PM
 */

namespace cms\lib\abstracts;

use cms\CMS;
use cms\lib\help\ControllerLoader;
use cms\lib\help\Lang;
use fm\FM;
use fm\lib\help\Numeric;
use fm\lib\help\Request;
use fm\lib\publisher\Response;

abstract class CmsStart
{
    /**
     * @var \cms\lib\interfaces\Controller
     */
    protected $activeController;

    /**
     * @var Response
     */
    protected $response;

    public function __construct()
    {
        ControllerLoader::setCurrentLangController(Request::name('controller', FM_STRING, FM_GET));
        $this->authorize();
    }

    /**
     * Run app
     */
    public function run()
    {
        CMS::setView();
        CMS::setSiteDomain();
        CMS::setGlobalConfig();

        $strView = Request::name('view', FM_STRING, FM_GET);

        if(!empty($strView))
            CMS::$viewFormat = $strView;

        $this->response = ControllerLoader::load(ControllerLoader::getCurrent(), true);

        $arrResponseCode = FM::includer(FM_CONFIG . 'responseCode.php', false);

        if(isset($arrResponseCode[$this->response->getResponseCode()]))
            FM::header($arrResponseCode[$this->response->getResponseCode()], true, $this->response->getResponseCode());

        if(CMS::$viewFormat == FM_JSON)
        {
            FM::header('Content-Type: application/json', true, $this->response->getResponseCode());
            echo json_encode($this->response->getData());
        }
        else
        {
            CMS::$view->assign('data', $this->response->getData());
            CMS::$view->display($this->response->getTemplatePath());

            CMS::$view->assign("lab", Lang::getLab());
            CMS::$view->assign("config", CMS::getGlobalConfig());
            CMS::$view->assign('domain', CMS::getSiteDomain());
            CMS::$view->assign('ch_lang', ControllerLoader::getChLang());
            CMS::$view->assign('lang', Lang::getCurrent());
            CMS::$view->assign('controllers', ControllerLoader::getControllersPathFromLang());
            CMS::$view->assign('controller', ControllerLoader::getCurrent());
            CMS::$view->assign('admin_theme', CMS::getAdminTheme());
            CMS::$view->assign('langs', Lang::getLang());

            CMS::$view->show();
        }
    }

    /**
     * Set user permission by token and user id
     */
    public function authorize()
    {
        $strToken = FM::getCustomHttpHeader('token');

        if(isset($strToken))
        {
            $intUserId = FM::getCustomHttpHeader('user');

            if(isset($intUserId))
            {
                $intUserId = Numeric::intVal($intUserId);

                $strSql = "SELECT id, permission FROM " . CMS::$dbPrefix . "users
                            WHERE token = :token AND id = :id AND token_expire_time > UNIX_TIMESTAMP() LIMIT 1";

                $arrParams[':token'] = $strToken;
                $arrParams[':id'] = $intUserId;

                CMS::$db->query($strSql, $arrParams);
                if(CMS::$db->rowCount() > 0)
                {
                    $arrData = CMS::$db->fetch(FM_FETCH_ASSOC, false);

                    if(isset($arrData['permission']))
                        CMS::$userPermission = $arrData['permission'];
                }
            }
        }
    }
}