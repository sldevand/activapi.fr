<?php

namespace App\Backend\Modules\Mesures;

use Entity\Mesure;
use Entity\Sensor;
use Model\SensorsManagerPDO;
use Model\MesuresManagerPDO;
use OCFram\BackController;
use OCFram\DateFactory;
use OCFram\HTTPRequest;
use DateTime;
use DateTimeZone;

/**
 * Class MesuresController
 * @package App\Backend\Modules\Mesures
 */
class MesuresController extends BackController
{
    protected MesuresManagerPDO $manager;

    /**
     * MesuresController constructor.
     * @param \OCFram\Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(\OCFram\Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('Mesures');
    }

    /**
     * @throws \Exception
     */
    public function executeSensor(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion de sensor');

        $sensorID = $request->getData('id_sensor');

        if ($request->getExists('dateMin') && $request->getExists('dateMax')) {
            $dateMin = $request->getData('dateMin');
            $dateMax = $request->getData('dateMax');
        } else {
            $day = $request->getData('day');
            $dateLimits = DateFactory::getDateLimits($day);

            $dateMin = $dateLimits['dateMin'];
            $dateMax = $dateLimits['dateMax'];
        }

        $dateMin = new DateTime($dateMin, new DateTimeZone('Europe/Paris'));
        $dateMax = new DateTime($dateMax, new DateTimeZone('Europe/Paris'));

        if ($dateMin > $dateMax) {
            $dateTemp = $dateMax;
            $dateMax = $dateMin;
            $dateMin = $dateTemp;
        }

        $dateMinFull = $dateMin->format('Y-m-d 00:00:00');
        $dateMaxFull = $dateMax->format('Y-m-d 23:59:59');
        $dateMinOnly = $dateMin->format('Y-m-d');
        $dateMaxOnly = $dateMax->format('Y-m-d');

        $now = DateFactory::createDateFromStr('now');
        $nowDateOnly = $now->format('Y-m-d');
        $today = false;
        if ($dateMinOnly == $dateMaxOnly && $nowDateOnly == $dateMinOnly) {
            $today = true;
        }

        $listeMesures = [];
        $cacheFile = $sensorID . '-' . $dateMinOnly . '-' . $dateMaxOnly;
        if (!$this->cache()->getData($cacheFile)) {
            $listeMesures = $this->manager->getSensorList($sensorID, $dateMinFull, $dateMaxFull);
            if (!$today) {
                $this->cache()->saveData($cacheFile, $listeMesures);
            }
        }

        $nom = '';
        $id = '';
        if ($listeMesures) {
            $nom = $listeMesures[0]->nom();
            $id = $listeMesures[0]->id();
        }

        $this->page->addVar('nom', $nom);
        $this->page->addVar('id', $id);
        $this->page->addVar('listeMesures', $listeMesures);
        $this->page->addVar('sensorID', $sensorID);
    }

    /**
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executeInsert(HTTPRequest $request)
    {
        $sensorId = $request->getData('id_sensor');

        /** @var \Entity\Sensor $sensorEntity */
        $sensorEntity = $this->manager->getSensor($sensorId);

        if (is_array($sensorEntity)) {
            $sensorEntity = current($sensorEntity);
        }

        if (!$sensorEntity instanceof Sensor) {
            return $this->page->addVar('measure', 'No entity found with id ' . $sensorId);
        }

        $valeur1 = $request->getData('valeur1');
        $valeur2 = $request->getData('valeur2');

        $t_min_lim = -20;
        $t_max_lim = 50;
        $h_min_lim = 0;
        $h_max_lim = 100;

        if ($sensorEntity->categorie() == 'teleinfo') {
            $t_min_lim = 0;
            $t_max_lim = 1_000_000;
            $h_min_lim = 0;
            $h_max_lim = 10_000;
        }

        if (($valeur1 !== '' && ($valeur1 < $t_min_lim || $valeur1 > $t_max_lim)) ||
                ($valeur2 !== '' && ($valeur2 < $h_min_lim || $valeur2 > $h_max_lim))) {
            return $this->page->addVar('measure', ['error' => "limites de mesure dépassées! -> valeur1=$valeur1 , valeur2=$valeur2"]);
        }

        $derniereValeur1 = $sensorEntity->valeur1();
        $sensorEntity->setValeur1($valeur1);
        $sensorEntity->setValeur2($valeur2);

        $diff = abs($derniereValeur1 - $valeur1);

        if ($diff > 0) {
            $this->page->addVar('measure', $this->manager->addWithSensorId($sensorEntity));
        } else {
            $this->page->addVar('measure', 0);
        }

        /** @var SensorsManagerPDO $sensorManager */
        $sensorManager = $this->managers->getManagerOf('Sensors');
        $sensorManager->sensorActivityUpdate($sensorEntity, 1);

        $dateMinFull = DateFactory::createDateFromStr('now');
        $dateMaxFull = DateFactory::createDateFromStr('now');

        $dateMin = $dateMinFull->format('Y-m-d');
        $dateMax = $dateMaxFull->format('Y-m-d');

        $file = $sensorId . '-' . $dateMin . '-' . $dateMax;
        $this->cache()->setDataPath($file);
        $this->cache()->deleteFile();

        return $this->page();
    }

    /**
     * @return \OCFram\Page
     * @throws \Exception
     */
    public function executeInsertChacon(HTTPRequest $request)
    {
        $radioaddress = $request->getData('radioaddress') ?? '';
        $radioaddress = urldecode((string) $radioaddress);

        /** @var \Entity\Sensor $sensorEntity */
        $sensorEntity = $this->manager->getSensor($radioaddress, 'radioaddress');

        if (is_array($sensorEntity)) {
            $sensorEntity = current($sensorEntity);
        }

        if (!$sensorEntity instanceof Sensor || !$sensorEntity->id()) {
            return $this->page->addVar('measure', 'No entity found with radioaddress ' . $radioaddress);
        }

        $valeur1 = $request->getData('valeur1');
        $valeur2 = $request->getData('valeur2');

        if ($valeur1 !== '' && ($valeur1 < 0 || $valeur1 > 1) ||
                $valeur2 !== '' && ($valeur2 < 0 || $valeur2 > 1)) {
            return $this->page->addVar('measure', ['error' => "limites de mesure dépassées! -> valeur1=$valeur1 , valeur2=$valeur2"]);
        }

        $sensorEntity->setValeur1($valeur1);
        $sensorEntity->setValeur2($valeur2);

        $mesure = new Mesure(
            [
                'id_sensor' => (string) $sensorEntity->id(),
                'temperature' => $valeur1,
                'hygrometrie' => $valeur2
            ]
        );

        if ($res = $this->manager->addWithSensorId($sensorEntity)) {
            $this->page->addVar('measure', $mesure);
        } else {
            return $this->page->addVar('measure', ['error' => 'Could not save the measure']);
        }

        /** @var SensorsManagerPDO $sensorManager */
        $sensorManager = $this->managers->getManagerOf('Sensors');
        $sensorManager->sensorActivityUpdate($sensorEntity, 1);

        $dateMinFull = DateFactory::createDateFromStr('now');
        $dateMaxFull = DateFactory::createDateFromStr('now');

        $dateMin = $dateMinFull->format('Y-m-d');
        $dateMax = $dateMaxFull->format('Y-m-d');

        $file = $sensorEntity->id() . '-' . $dateMin . '-' . $dateMax;
        $this->cache()->setDataPath($file);
        $this->cache()->deleteFile();
    }

    /**
     * @throws \Exception
     */
    public function executeSensorStruct(HTTPRequest $request)
    {
        if ($radioid = $request->getData('radioid')) {
            $sensor = $this->manager->getSensor($radioid);
        }

        if ($radioaddress = $request->getData('radioaddress')) {
            $radioaddress = urldecode((string) $radioaddress);
            $sensor = $this->manager->getSensor($radioaddress, 'radioaddress');
        }

        $this->page->addVar('sensor', [$sensor]);
    }

    /**
     * @throws \Exception
     */
    public function executeSensors(HTTPRequest $request)
    {
        $categorie = $request->getData('categorie');
        if ($categorie === null) {
            $categorie = '';
        }
        /** @var \Model\SensorsManagerPDO $sensorsManager */
        $sensorsManager = $this->managers->getManagerOf('Sensors');
        $sensors = $sensorsManager->getList($categorie);

        $this->page->addVar('sensors', $sensors);
    }
}
