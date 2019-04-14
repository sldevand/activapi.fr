<?php

namespace App\Backend\Modules\Scenarios;

use App\Backend\Modules\AbstractScenarioManagersController;
use Entity\Scenario\Scenario;
use Exception;
use OCFram\Application;
use OCFram\HTTPRequest;

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
     * @param \OCFram\HTTPRequest $httpRequest
     * @throws Exception
     */
    public function executePost($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::POST);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkJsonBodyId($jsonPost);
            $entity = new $this->entity($jsonPost);
            $entity->setScenarioSequences($this->fetchScenarioSequences($jsonPost));
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
            $entity->setScenarioSequences($this->fetchScenarioSequences($jsonPost));
            $entityId = $this->manager->save($entity);
            $persisted = $this->manager->getUnique($entityId);
            http_response_code(202);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }

        return $this->page->addVar('data', $persisted);
    }

    /**
     * @param array $jsonPost
     * @return array
     * @throws Exception
     */
    protected function fetchScenarioSequences($jsonPost)
    {
        $scenarioSequences = [];
        if ($jsonPost['scenarioSequences']) {
            foreach ($jsonPost['scenarioSequences'] as $scenarioSequence) {
                $scenarioSequences[] = $this->getScenarioSequenceManager()->getUnique($scenarioSequence['id']);
            }
        }

        return $scenarioSequences;
    }
}
