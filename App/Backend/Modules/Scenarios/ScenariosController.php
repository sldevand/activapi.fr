<?php

namespace App\Backend\Modules\Scenarios;

use App\Backend\Modules\AbstractScenarioManagersController;
use Entity\Scenario\Scenario;
use Entity\Scenario\ScenarioSequence;
use Exception;
use OCFram\Application;
use OCFram\HTTPRequest;
use Psinetron\SocketIO;

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

    /**
     * @param HTTPRequest $httpRequest
     * @throws Exception
     */
    public function executePost($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::POST);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkJsonBodyId($jsonPost);
            $entity = new $this->entity($jsonPost);
            $entity->setScenarioSequences($this->getScenarioSequences($jsonPost));
            $entityId = $this->manager->save($entity);
            $persisted = $this->manager->getUnique($entityId);
            http_response_code(201);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }

        return $this->page->addVar('data', $persisted);
    }

    /**
     * @param HTTPRequest $httpRequest
     * @throws Exception
     */
    public function executePut($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::PUT);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkNotJsonBodyId($jsonPost);
            $entity = new $this->entity($jsonPost);
            $this->deleteScenarioSequences($jsonPost);
            $entity->setScenarioSequences($this->getScenarioSequences($jsonPost));
            $entityId = $this->manager->save($entity);
            $persisted = $this->manager->getUnique($entityId);
            http_response_code(202);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }

        return $this->page->addVar('data', $persisted);
    }

    /**
     * @param HTTPRequest $httpRequest
     * @throws Exception
     */
    public function executeReset($httpRequest)
    {
        try {
            $this->manager->resetScenarioStatuses();
            http_response_code(202);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }

        $this->page->addVar('data', ['success' => 'Scenarios have been reset to stop value']);
    }

    /**
     * @param array $jsonPost
     * @return array|bool
     * @throws Exception
     */
    protected function getScenarioSequences($jsonPost)
    {
        if (empty($jsonPost['scenarioSequences'])) {
            return false;
        }

        $scenarioSequences = [];
        foreach ($jsonPost['scenarioSequences'] as $scenarioSequence) {
            $scenarioSequences[] = new ScenarioSequence([
                'id' => $scenarioSequence['id'],
                'scenarioId' => $jsonPost['id'],
                'sequenceId' => $scenarioSequence['sequenceId']
            ]);
        }

        return $scenarioSequences;
    }

    /**
     * @param array $jsonPost
     * @return array|bool
     * @throws Exception
     */
    protected function deleteScenarioSequences($jsonPost)
    {
        if (empty($jsonPost['deletedScenarioSequences'])) {
            return false;
        }

        $scenarioSequences = [];
        foreach ($jsonPost['deletedScenarioSequences'] as $deletedScenarioSequence) {
            $this->getScenarioSequenceManager()->delete($deletedScenarioSequence);
        }

        return $scenarioSequences;
    }

    /**
     * @param HTTPRequest $request
     * @throws Exception
     */
    public function executeCommand(HTTPRequest $request)
    {
        $id = $request->getData('id');

        if (empty($id)) {
            return $this->page->addVar('output', ['error' => 'No id given']);
        }

        /** @var Scenario $scenario */
        $scenario = $this->manager->getUnique($id);
        if (empty($scenario)) {
            return $this->page->addVar('output', ['error' => 'No scenario on id ' . $id]);
        }

        $ip = $this->app()->config()->get('nodeIP');
        $port = $this->app()->config()->get('nodePort');
        $action = 'updateScenario';
        $dataJSON = json_encode($scenario);

        $socketio = new SocketIO();
        if (!$socketio->send($ip, $port, $action, $dataJSON)) {
            return $this->page->addVar('output', ['error' => 'Node error']);
        }

        return $this->page->addVar('output', ['message' => 'Ok']);
    }
}
