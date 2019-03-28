<?php

namespace App\Backend\Modules\Scenarios;

use App\Backend\Modules\AbstractScenarioManagersController;
use Entity\Scenario\Scenario;
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
        if ($httpRequest->method() !== 'GET') {
            $error = ["error" => 'Wrong method : ' . $httpRequest->method() . ', use GET method instead'];
            return $this->page()->addVar('scenarios', $error);
        }
        $scenarioManager = $this->getScenariosManager();

        try {
            $data = $scenarioManager->getAll();
        } catch (Exception $e) {
            $data = ["error" => $e->getMessage()];
            return $this->page()->addVar('scenarios', $data);
        }

        return $this->page()->addVar('scenarios', $data);
    }

    /**
     * @param HTTPRequest $httpRequest
     * @throws Exception
     */
    public function executePost($httpRequest)
    {
        if ($httpRequest->method() !== 'POST') {
            $error = ["error" => 'Wrong method : ' . $httpRequest->method() . ', use POST method instead'];
            return $this->page()->addVar('data', $error);
        }

        if (!$jsonPost = $httpRequest->getJsonPost()) {
            return $this->page->addVar('data', ['error' => 'No JSON body sent from client']);
        }

        if (!empty($jsonPost['id'])) {
            return $this->page->addVar('data', ['error' => 'JSON body contains an id, use PUT method instead']);
        }

        $scenario = new Scenario($jsonPost);

        try {
            $scenarioId = $this->getScenariosManager()->save($scenario);
        } catch (Exception $e) {
            $data = ["error" => $e->getMessage()];
            return $this->page()->addVar('data', $data);
        }

        $persisted = $this->getScenariosManager()->getUnique($scenarioId);

        return $this->page->addVar('data', $persisted);
    }

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executePut($httpRequest)
    {
        if ($httpRequest->method() !== 'PUT') {
            $error = ["error" => 'Wrong method : ' . $httpRequest->method() . ', use PUT method instead'];
            return $this->page()->addVar('data', $error);
        }

        if (!$jsonPost = $httpRequest->getJsonPost()) {
            return $this->page->addVar('data', ['error' => 'No JSON body sent from client']);
        }


        return $this->page->addVar('data', $scenario);
    }

    /**
     * @param HTTPRequest $httpRequest
     */
    public function executeDelete($httpRequest)
    {
        if ($httpRequest->method() !== 'DELETE') {
            $error = ["error" => 'Wrong method : ' . $httpRequest->method() . ', use DELETE method instead'];
            return $this->page()->addVar('data', $error);
        }
        // TODO: Implement executeDelete() method.
    }
}
