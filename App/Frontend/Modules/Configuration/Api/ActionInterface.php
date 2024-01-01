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
     * @return mixed
     */
    public function execute(HTTPRequest $request);
}
