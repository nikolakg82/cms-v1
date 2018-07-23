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

use cms\lib\interfaces as Interfaces;
use fm\lib\publisher\Response;

abstract class Controller implements Interfaces\Controller
{
    /**
     * @var Interfaces\Model
     */
    protected $model;

    /**
     * @var Response
     */
    protected $response;

    protected $path;

    public function setPath($strTable, $intId)
    {
        $this->path[$strTable] = $intId;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setModel(Interfaces\Model $objModel)
    {
        $this->model = $objModel;

        return $this;
    }

    public function setResponse(Response $objResponse)
    {
        $this->response = $objResponse;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getModel()
    {
        return $this->model;
    }
}