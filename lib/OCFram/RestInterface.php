<?php

namespace OCFram;

interface RestInterface
{
    /**
     * @param HTTPRequest $httpRequest
     */
    public function executeGet($httpRequest);

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executePost($httpRequest);

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executePut($httpRequest);

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executeDelete($httpRequest);
}
