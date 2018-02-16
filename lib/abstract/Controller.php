<?php

// @TODO - napraviti interface za controler, da to bude kao fancy, a i ako neko resi da pravi svoj kontroler da ima interface za njega

namespace cms\lib\abstracts;

use cms\lib\interfaces as Interfaces;

abstract class Controller implements Interfaces\Controller
{
    private $model;

    private $view;

    private $path;

    public function set_model($objModel)
    {
        $this->model = $objModel;
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

    public function setModel(Interfaces\Model $objModel)
    {
        $this->model = $objModel;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function run()
    {
        // TODO: Implement run() method.
    }
}