<?php

namespace App\Frontend;

use OCFram\Application;

/**
 * Class FrontendApplication
 * @package App\Frontend
 */
class FrontendApplication extends Application
{
    /**
     * FrontendApplication constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Frontend';
        $this->rootUri = ROOT;
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function run()
    {
        $controller = $this->getController();

        $controller->execute();
        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}
