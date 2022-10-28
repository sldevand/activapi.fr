<?php

namespace App\Frontend\Modules\Scenarios;

use OCFram\Application;
use Materialize\FormView;
use OCFram\BackController;
use Model\Scenario\ScenarioManagerPDOFactory;

abstract class AbstractScenariosController extends BackController
{
    use FormView;

    protected ScenarioManagerPDOFactory $scenarioManagerPDOFactory;

    public function __construct(Application $app, string $module, string $action) {
        parent::__construct($app, $module, $action);
        $this->scenarioManagerPDOFactory = new ScenarioManagerPDOFactory();
    }
}
