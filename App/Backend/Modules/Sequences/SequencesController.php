<?php

namespace App\Backend\Modules\Sequences;

use App\Backend\Modules\AbstractScenarioManagersController;
use Entity\Scenario\Sequence;
use Entity\Scenario\SequenceAction;
use Exception;
use OCFram\Application;
use OCFram\HTTPRequest;
use OCFram\RestInterface;

/**
 * Class SequencesController
 * @package App\Backend\Modules\Sequences
 */
class SequencesController extends AbstractScenarioManagersController implements RestInterface
{
    /**
     * SequencesController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);

        $this->manager = $this->getSequencesManager();
        $this->entity = Sequence::class;
    }

    /**
     * @param \OCFram\HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executePost($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::POST);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkJsonBodyId($jsonPost);
            unset($jsonPost['deletedSequenceActions']);
            $entity = new $this->entity($jsonPost);
            $entity->setSequenceActions($this->getSequenceActions($jsonPost));
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
     * @param \OCFram\HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executePut($httpRequest)
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::PUT);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkNotJsonBodyId($jsonPost);
            $this->deleteSequenceActions($jsonPost);
            unset($jsonPost['deletedSequenceActions']);
            $entity = new $this->entity($jsonPost);
            $entity->setSequenceActions($this->getSequenceActions($jsonPost));
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
     * @param array $jsonPost
     * @return array|bool
     * @throws Exception
     */
    protected function getSequenceActions($jsonPost)
    {
        if (empty($jsonPost['sequenceActions'])) {
            return false;
        }

        $sequenceActions = [];
        foreach ($jsonPost['sequenceActions'] as $sequenceAction) {
            $sequenceActions[] = new SequenceAction([
                'id' => $sequenceAction['id'],
                'sequenceId' => $jsonPost['id'],
                'actionId' => $sequenceAction['actionId']
            ]);
        }

        return $sequenceActions;
    }

    /**
     * @param array $jsonPost
     * @return array|bool
     * @throws Exception
     */
    protected function deleteSequenceActions($jsonPost)
    {
        if (empty($jsonPost['deletedSequenceActions'])) {
            return false;
        }

        $sequenceActions = [];
        foreach ($jsonPost['deletedSequenceActions'] as $deletedSequenceActions) {
            $this->getSequenceActionManager()->delete($deletedSequenceActions);
        }

        return $sequenceActions;
    }
}
