<?php

namespace App\Backend\Modules\Actions;

use App\Backend\Modules\AbstractScenarioManagersController;
use Entity\Scenario\Action;
use OCFram\Application;
use OCFram\RestInterface;

/**
 * Class ActionsController
 * @package App\Backend\Modules\Actions
 */
class ActionsController extends AbstractScenarioManagersController implements RestInterface
{
    /***
     * ActionsController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);

        $this->manager = $this->getActionManager();
        $this->entity = Action::class;
    }

    /**
     * @param \OCFram\HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executePost($httpRequest)
    {
        $page = parent::executePost($httpRequest);
        $this->deleteActionCache('index', 'Frontend');

        return $page;
    }

    /**
     * @param \OCFram\HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executePut($httpRequest)
    {
        $page = parent::executePut($httpRequest);
        $this->deleteActionCache('index', 'Frontend');

        return $page;
    }
}
