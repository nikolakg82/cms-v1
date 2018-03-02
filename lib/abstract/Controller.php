<?php

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