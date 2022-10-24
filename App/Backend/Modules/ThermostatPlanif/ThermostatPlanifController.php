<?php

namespace App\Backend\Modules\ThermostatPlanif;

use Entity\ThermostatPlanif;
use Exception;
use Model\ThermostatPlanifManagerPDO;
use OCFram\AbstractRestController;
use OCFram\Application;
use OCFram\HTTPRequest;
use OCFram\Page;

/**
 * Class ThermostatPlanifController
 * @package App\Backend\Modules\ThermostatPlanif
 */
class ThermostatPlanifController extends AbstractRestController
{
    /**
     * @var \Model\ThermostatPlanifManagerPDO $manager
     */
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
        $this->manager = $this->managers->getManagerOf('ThermostatPlanif');
        $this->entity = ThermostatPlanif::class;
    }

    /**
     * @param HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws Exception
     */
    public function executeGet($httpRequest): Page
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::GET);
            $nomid = $httpRequest->getData('nomid');
            $day = $httpRequest->getData('jour');
            if ($nomid && $day) {
                $entities = $this->manager->getByNomIdAndDay($nomid, $day);
            } else {
                $entities = $this->manager->getList($nomid);
            }
        } catch (Exception $exception) {
            return $this->page()->addVar('data', ["error" => $exception->getMessage()]);
        }

        return $this->page()->addVar('data', $entities);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeName(HTTPRequest $request)
    {
        /** @var ThermostatPlanifManagerPDO $manager */
        $manager = $this->managers->getManagerOf('ThermostatPlanif');
        $thermostatPlanifs = $request->getExists('id')
            ? $manager->getNom($request->getData('id'))
            : $manager->getNoms();
        $this->page->addVar('thermostatPlanifs', $thermostatPlanifs);
    }

    /**
     * @param HTTPRequest $httpRequest
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executePut($httpRequest): Page
    {
        try {
            $this->checkMethod($httpRequest, HTTPRequest::PUT);
            $jsonPost = $httpRequest->getJsonPost();
            $this->checkNotJsonBodyId($jsonPost);
            if (!empty($jsonPost['timetable'])) {
               $this->validateTimetable($jsonPost['timetable']);
            }
            $entity = new $this->entity($jsonPost);
            if (!$this->manager->save($entity)) {
                throw new Exception("L'entité ThermostatPlanif" . $entity->getId() . " n'a pas pu être sauvegardée");
            }
            $persisted = $this->manager->getByNomIdAndDay($entity->getNomid(), $entity->getJour());
            http_response_code(202);
        } catch (Exception $e) {
            http_response_code(400);
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }
        $this->deleteActionCache('index', 'Frontend');

        return $this->page->addVar('data', $persisted);
    }

    /**
     * @param string $timetable
     * @return void
     * @throws \Exception
     */
    protected function validateTimetable(string $timetable) {
        /** @var \Model\ThermostatModesManagerPDO $modesManager */
        $modesManager = $this->managers->getManagerOf('ThermostatModes');
        $modeIds = array_flip($modesManager->getModeIds());

        $timetableArray = json_decode($timetable);
        foreach ($timetableArray as $row) {
            $rowArray = explode('-', $row);
            $minute = $rowArray[0];
            if ($minute < 0 || $minute > 1439) {
                throw new Exception("La minute $minute doit être comprise entre 0 et 1439");
            }
            $modeId = $rowArray[1];
            if (!isset($modeIds[$modeId])) {
                throw new Exception("Le mode $modeId n'existe pas");
            }

            if (count($rowArray) !== 2) {
                throw new Exception("Une ligne dans la timetable est mal formatée: $row");
            }

        }
    }
}
