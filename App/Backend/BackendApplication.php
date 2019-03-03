<?php

namespace App\Backend;

use OCFram\Application;

/**
 * Class BackendApplication
 * @package App\Backend
 */
class BackendApplication extends Application
{
    /**
     * BackendApplication constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Backend';
    }

    public function run()
    {
        $controller = $this->getController();
        $controller->execute();
        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->sendJSON();
    }
}
