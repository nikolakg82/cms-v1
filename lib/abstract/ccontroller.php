<?php

abstract class Ccontroller
{
    private $model;

    private $view;

    private $path;

    public function set_model($objModel)
    {
        $this->model = $objModel;
        $this->get_view()->set_model($objModel);
    }

    public function set_view($objView)
    {
        $this->view = $objView;
    }

    public function set_path($strTable, $intId)
    {
        $this->path[$strTable] = $intId;
    }

    public function get_model()
    {
        return $this->model;
    }

    public function get_view()
    {
       return $this->view;
    }

    public function get_path()
    {
        return $this->path;
    }
}