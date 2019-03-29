<?php

namespace App\Backend\Modules\Actions;

use App\Backend\Modules\AbstractScenarioManagersController;
use Entity\Scenario\Action;
use OCFram\Application;
use OCFram\RestInterface;

/**
 * Class ActionsController
 * @package App\Backend\Modules\Scenarios
 */
class ActionsController extends AbstractScenarioManagersController implements RestInterface
{
    /**
     * ScenariosController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);

        $this->manager = $this->getActionManager();
        $this->entity = Action::class;
    }
}
