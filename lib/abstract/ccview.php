<?php

abstract class Ccview
{
    private $model;

    public function set_model($objModel)
    {
        $this->model = $objModel;
    }

    public function get_model()
    {
        return $this->model;
    }
}