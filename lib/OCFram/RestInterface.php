<?php

namespace OCFram;

interface RestInterface
{
    /**
     * @param HTTPRequest $httpRequest
     */
    public function get($httpRequest);

    /**
     * @param HTTPRequest $httpRequest
     */
    public function post($httpRequest);

    /**
     * @param HTTPRequest $httpRequest
     */
    public function put($httpRequest);

    /**
     * @param HTTPRequest $httpRequest
     */
    public function delete($httpRequest);
}
