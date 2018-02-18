<?php

namespace cms\lib\abstracts;

use cms\lib\interfaces\Model;

abstract class View
{
    private $model;

    public function setModel(Model $objModel)
    {
        $this->model = $objModel;
    }

    public function getModel()
    {
        return $this->model;
    }
}