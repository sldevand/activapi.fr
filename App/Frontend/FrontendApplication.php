<?php

namespace App\Frontend;

use OCFram\Application;
use OCFram\Route;

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

    /**
     * @param \OCFram\Route $route
     */
    protected function checkRoutePermission(Route $route)
    {
        if ($route->getScope() === Route::SCOPE_PRIVATE && !$this->user()->isAuthenticated()) {
            $this->user()->setFlash('You cannot access this page because you are not logged in!');
            $this->httpResponse->redirect($this->root . '/user/login');
        }
    }
}
