<?php

namespace App\Backend\Modules\Crontab;

use Entity\Crontab\Crontab;
use Exception;
use Model\Crontab\CrontabManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;
use OCFram\RestInterface;

/**
 * Class CrontabController
 * @package App\Backend\Modules\Crontab
 */
class CrontabController extends BackController implements RestInterface
{

    /** @var CrontabManagerPDO $manager */
    protected $manager;

    /** @var string $entity */
    protected $entity;

    /**
     * CrontabController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws Exception
     */
    public function __construct(
        Application $app,
        string $module,
        string $action
    ) {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('Crontab\Crontab');
        $this->entity = Crontab::class;
    }

    /**
     * @param HTTPRequest $httpRequest
     * @return array|null|\OCFram\Entity|\OCFram\Page
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

        return $this->page->addVar('data', ['success' => $entity->getName() . ' has been deleted']);
    }
}
