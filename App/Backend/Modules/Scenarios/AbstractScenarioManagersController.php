<?php

namespace App\Backend\Modules\Scenarios;

use OCFram\Application;
use OCFram\AbstractRestController;
use Model\Scenario\ScenarioManagerPDOFactory;

/**
 * Class AbstractScenarioManagersController
 * @package App\Backend\Modules\Scenarios
 */
abstract class AbstractScenarioManagersController extends AbstractRestController
{
    protected ScenarioManagerPDOFactory $scenarioManagerPDOFactory;

    public function __construct(Application $app, string $module, string $action) {
        parent::__construct($app, $module, $action);
        $this->scenarioManagerPDOFactory = new ScenarioManagerPDOFactory();
    }
}
