<?php

namespace App\Frontend\Modules\Configuration\Api;

use OCFram\HTTPRequest;

/**
 * Interface ActionInterface
 * @package App\Frontend\Modules\Configuration\Api
 */
interface ActionInterface
{
    /**
     * @param \OCFram\HTTPRequest $request
     * @return mixed
     */
    public function execute(HTTPRequest $request);
}
