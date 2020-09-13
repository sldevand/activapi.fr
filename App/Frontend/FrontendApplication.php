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
     */
    public function run()
    {
        $controller = $this->getController();

        if ($controller->isRestricted() && !$this->user()->isAuthenticated()) {
            $homeUrl = $this->httpRequest->baseUrl() . $this->router->getRoot() . '/';
            $this->user->setFlash('You are not logged in');
            $this->httpResponse->redirect($homeUrl);
        }

        $controller->execute();
        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}
