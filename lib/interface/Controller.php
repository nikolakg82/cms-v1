<?php

/**
 * @copyright Copyright (c) 2005-2018 MSD - All Rights Reserved
 * @link http://www.nikolamilenkovic.com
 * @email info@nikolamilenkovic.com
 * @author Nikola Milenkovic info@nikolamilenkovic.com dzoni82.kg@gmail.com http://www.nikolamilenkovic.com
 * Date: 2/16/2018
 * Time: 2:51 PM
 */

namespace cms\lib\interfaces;

use fm\lib\publisher\Response;

interface Controller
{
    /**
     * @param Model $objModel
     * @return $this
     */
    public function setModel(Model $objModel);

    /**
     * @param Response $objResponse
     * @return $this
     */
    public function setResponse(Response $objResponse);

    /**
     * @return Response
     */
    public function getResponse();

    /**
     * @return Model
     */
    public function getModel();
}