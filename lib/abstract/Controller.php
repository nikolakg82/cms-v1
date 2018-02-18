<?php

// @TODO - napraviti interface za controler, da to bude kao fancy, a i ako neko resi da pravi svoj kontroler da ima interface za njega

namespace cms\lib\abstracts;

use cms\lib\interfaces as Interfaces;

abstract class Controller implements Interfaces\Controller
{
    /**
     * @var Interfaces\Model
     */
    protected $model;

    protected $view;

    protected $path;

    public function setView($objView)
    {
        $this->view = $objView;
    }

    public function setPath($strTable, $intId)
    {
        $this->path[$strTable] = $intId;
    }

    public function getView()
    {
       return $this->view;
    }

    public function getPath()
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