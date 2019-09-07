<?php

namespace App\Backend\Modules;

use Exception;
use Model\ActionneursManagerPDO;
use Model\Scenario\ActionManagerPDO;
use Model\Scenario\ScenarioSequenceManagerPDO;
use Model\Scenario\ScenariosManagerPDO;
use Model\Scenario\SequenceActionManagerPDO;
use Model\Scenario\SequencesManagerPDO;
use OCFram\BackController;
use OCFram\HTTPRequest;
use OCFram\RestInterface;

/**
 * Class AbstractScenarioManagersController
 * @package App\Backend\Modules\Scenarios
 */
abstract class AbstractScenarioManagersController extends BackController implements RestInterface
{
    /**
     * @var ScenariosManagerPDO $manager
     */
    protected $manager;

    /**
     * @var string $entity
     */
    protected $entity;

    /**
     * @param HTTPRequest $httpRequest
     * @return \Entity\Scenario\Scenario[] | \Entity\Scenario\Scenario
     * @throws Exception
     */
    public function executeGet($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::GET);
            $id = $httpRequest->getData('id');
            if ($id) {
                $entities = $this->manager->getUnique($id);
            } else {
                $entities = $this->manager->getAll($id);
            }
        } catch (Exception $e) {
            return $this->page()->addVar('data', ["error" => $e->getMessage()]);
        }

        $this->page()->addVar('data', $entities);
        return $entities;
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
     */
    public function executeDelete($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::DELETE);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkNotJsonBodyId($jsonPost);
            $entity = $this->manager->getUnique($jsonPost['id']);
            $deleted = $this->manager->delete($jsonPost['id']);
            http_response_code(202);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }

        if (!$deleted) {
            return $this->page->addVar('data', ['error' => 'No ' . get_class($entity) . ' was deleted']);
        }

        return $this->page->addVar('data', ['success' => $entity->getNom() . ' has been deleted']);
    }

    /**
     * @return ScenariosManagerPDO
     */
    public function getScenariosManager()
    {
        $managers = [
            'sequencesManagerPDO' => $this->getSequencesManager(),
            'scenarioSequenceManagerPDO' => $this->getScenarioSequenceManager()
        ];

        return $this->managers->getManagerOf(
            'Scenario\Scenarios',
            $managers
        );
    }

    /**
     * @return SequencesManagerPDO
     */
    public function getSequencesManager()
    {
        $managers = [
            'actionManagerPDO' => $this->getActionManager(),
            'sequenceActionManagerPDO' => $this->getSequenceActionManager()
        ];
        return $this->managers->getManagerOf('Scenario\Sequences', $managers);
    }

    /**
     * @return ActionManagerPDO
     */
    public function getActionManager()
    {
        $managers = ['actionneursManagerPDO' => $this->getActionneursManager()];
        return $this->managers->getManagerOf('Scenario\Action', $managers);
    }

    /**
     * @return ActionneursManagerPDO
     */
    public function getActionneursManager()
    {
        return $this->managers->getManagerOf('Actionneurs');
    }

    /**
     * @return SequenceActionManagerPDO
     */
    public function getSequenceActionManager()
    {
        return $this->managers->getManagerOf('Scenario\SequenceAction');
    }

    /**
     * @return ScenarioSequenceManagerPDO
     */
    public function getScenarioSequenceManager()
    {
        return $this->managers->getManagerOf('Scenario\ScenarioSequence');
    }
}
