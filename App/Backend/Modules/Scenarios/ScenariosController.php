<?php

namespace App\Backend\Modules\Scenarios;

use Exception;
use OCFram\Application;
use OCFram\HTTPRequest;
use Entity\Scenario\Scenario;
use Entity\Scenario\ScenarioSequence;
use Model\Scenario\ScenarioSocketIoSender;

/**
 * Class ScenariosController
 * @package App\Backend\Modules\Scenarios
 */
class ScenariosController extends AbstractScenarioManagersController
{
    /** @var \Model\Scenario\ScenariosManagerPDO */
    protected $manager;
    
    /**
     * ScenariosController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);
        $this->manager = $this->scenarioManagerPDOFactory->getScenariosManager();
        $this->entity = Scenario::class;
    }

    /**
     * @param HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws Exception
     */
    public function executeGetAll($httpRequest) {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::GET);
            $entities = $this->manager->getAll(null, false);
        } catch (Exception $e) {
            return $this->page()->addVar('data', ["error" => $e->getMessage()]);
        }

        $this->page()->addVar('data', $entities);

        return $entities;
    }

    /**
     * @param HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws Exception
     */
    public function executePost($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::POST);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkJsonBodyId($jsonPost);
            unset($jsonPost['deletedScenarioSequences']);
            $entity = new $this->entity($jsonPost);
            $entity->setScenarioSequences($this->getScenarioSequences($jsonPost));
            $entityId = $this->manager->save($entity);
            $persisted = $this->manager->getUnique($entityId);
            http_response_code(201);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }
        $this->deleteActionCache('index', 'Frontend');

        return $this->page->addVar('data', $persisted);
    }

    /**
     * @param HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws Exception
     */
    public function executePut($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::PUT);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkNotJsonBodyId($jsonPost);
            $this->deleteScenarioSequences($jsonPost);
            unset($jsonPost['deletedScenarioSequences']);
            unset($jsonPost['startTime']);
            unset($jsonPost['stopTime']);
            unset($jsonPost['remainingTime']);
            $entity = new $this->entity($jsonPost);
            $entity->setScenarioSequences($this->getScenarioSequences($jsonPost));
            $entityId = $this->manager->save($entity);
            $persisted = $this->manager->getUnique($entityId);
            http_response_code(202);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }
        $this->deleteActionCache('index', 'Frontend');

        return $this->page->addVar('data', $persisted);
    }

    /**
     * @param HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws Exception
     */
    public function executeReset($httpRequest)
    {
        try {
            $this->manager->resetScenarioStatuses();
            $this->deleteActionCache('index', 'Frontend');
            http_response_code(202);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }

        return $this->page->addVar('data', ['success' => 'Scenarios have been reset to stop value']);
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
            $this->scenarioManagerPDOFactory->getScenarioSequenceManager()->delete($deletedScenarioSequence);
        }

        return $scenarioSequences;
    }

    /**
     * @param HTTPRequest $request
     * @return \OCFram\Page
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

        $socketIoSender = new ScenarioSocketIoSender($this->app());
        if (!$socketIoSender->send($scenario)) {
            return $this->page->addVar('output', ['error' => 'Node error']);
        }

        $this->deleteActionCache('index', 'Frontend');

        return $this->page->addVar('output', ['message' => 'Ok']);
    }
}
