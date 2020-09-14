<?php

namespace App\Backend;

use OCFram\Application;
use OCFram\Route;

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
        $this->rootUri = ROOT_API;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function run()
    {
        $controller = $this->getController();
        $controller->execute();
        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->sendJSON();

        return 0;
    }

    /**
     * @param \OCFram\Route $route
     */
    protected function checkRoutePermission(Route $route)
    {
        if ($route->getScope() === Route::SCOPE_PRIVATE && !$this->user()->isAuthenticated()) {
            $this->user()->setFlash('You cannot access this page because you are not logged in!');
            $this->httpResponse->redirect($this->root);
        }
    }
}
