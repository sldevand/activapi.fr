<?php

namespace OCFram;

use Exception;
use OCFram\HTTPRequest;
use OCFram\RestInterface;
use OCFram\BackController;

/**
 * Class AbstractRestController
 * @package OCFram
 */
abstract class AbstractRestController extends BackController implements RestInterface
{
    /**
     * @var \Model\ManagerPDO $manager
     */
    protected $manager;

    /**
     * @var string $entity
     */
    protected $entity;

    /**
     * @param HTTPRequest $httpRequest
     * @return \OCFram\Page
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
                $entities = $this->manager->getAll($id, true);
            }
        } catch (Exception $e) {
            return $this->page()->addVar('data', ["error" => $e->getMessage()]);
        }

        return $this->page()->addVar('data', $entities);
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
     * @return \OCFram\Page
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
     * @return \OCFram\Page
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

        return $this->page->addVar('data', ['success' => $entity->getId() . ' has been deleted']);
    }
}
