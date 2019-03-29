<?php

namespace App\Backend\Modules\Scenarios;

use App\Backend\Modules\AbstractScenarioManagersController;
use Entity\Scenario\Scenario;
use OCFram\Application;

/**
 * Class ScenariosController
 * @package App\Backend\Modules\Scenarios
 */
class ScenariosController extends AbstractScenarioManagersController
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

        $this->manager = $this->getScenariosManager();
        $this->entity = Scenario::class;
    }
}
