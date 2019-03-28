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
    public function exectuePost($httpRequest);

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executePut($httpRequest);

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executeDelete($httpRequest);
}
