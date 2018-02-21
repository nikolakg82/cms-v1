<?php

/**
 * Created by PhpStorm.
 * User: Nikola
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

    /**
     * @return Response
     */
    public function run();
}