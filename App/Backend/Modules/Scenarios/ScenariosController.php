<?php

namespace App\Backend\Modules\Scenarios;

use App\Backend\Modules\AbstractScenarioManagersController;
use Exception;
use OCFram\HTTPRequest;
use OCFram\RestInterface;

/**
 * Class ScenariosController
 * @package App\Backend\Modules\Scenarios
 */
class ScenariosController extends AbstractScenarioManagersController implements RestInterface
{
    /**
     * @param HTTPRequest $httpRequest
     * @throws Exception
     */
    public function executeGet($httpRequest)
    {
        $scenarioManager = $this->getScenariosManager();

        try {
            $scenarios = $scenarioManager->getAll();
        } catch (Exception $e) {
            $scenarios = ["error" => $e->getMessage()];
        }


        $this->page()->addVar('scenarios', $scenarios);
    }

    /**
     * @param HTTPRequest $httpRequest
     */
    public function exectuePost($httpRequest)
    {
        // TODO: Implement exectuePost() method.
    }

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executePut($httpRequest)
    {
        // TODO: Implement executePut() method.
    }

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executeDelete($httpRequest)
    {
        // TODO: Implement executeDelete() method.
    }
}
