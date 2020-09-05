<?php

namespace App\Backend\Modules\Mesures;

use DateTime;
use DateTimeZone;
use Entity\Mesure;
use OCFram\BackController;
use OCFram\DateFactory;
use OCFram\HTTPRequest;

/**
 * Class MesuresController
 * @package App\Backend\Modules\Mesures
 */
class MesuresController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeDelete(HTTPRequest $request)
    {
        $id = $request->getData('id');
        $this->managers->getManagerOf('Mesures')->delete($id);
        $this->app->user()->setFlash('La mesure a bien été supprimée !');
        $this->app->httpResponse()->redirect('.');
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des mesures');
    }

    /**
     * @param \OCFram\HTTPRequest $request
     * @throws \Exception
     */
    public function executeSensor(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion de sensor');
        $manager = $this->managers->getManagerOf('Mesures');

        $sensorID = $request->getData("id_sensor");

        if ($request->getExists("dateMin") && $request->getExists("dateMax")) {
            $dateMin = $request->getData("dateMin");
            $dateMax = $request->getData("dateMax");
        } else {
            $day = $request->getData("day");
            $dateLimits = $this->getDateLimits($day);

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

        $dateMinFull = $dateMin->format("Y-m-d 00:00:00");
        $dateMaxFull = $dateMax->format("Y-m-d 23:59:59");
        $dateMinOnly = $dateMin->format("Y-m-d");
        $dateMaxOnly = $dateMax->format("Y-m-d");

        $now = DateFactory::createDateFromStr("now");
        $nowDateOnly = $now->format("Y-m-d");
        $today = false;
        if ($dateMinOnly == $dateMaxOnly && $nowDateOnly == $dateMinOnly) {
            $today = true;
        }

        $listeMesures = [];
        $cacheFile = $sensorID . '-' . $dateMinOnly . '-' . $dateMaxOnly;
        if (! $this->cache()->getData($cacheFile)) {
            $listeMesures = $manager->getSensorList($sensorID, $dateMinFull, $dateMaxFull);
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

    /***
     * @param string $day
     * @return array
     */
    public function getDateLimits($day)
    {
        switch ($day) {
            case "today":
                $now = DateFactory::createDateFromStr("now");
                $dateMin = $now->format("Y-m-d");
                $dateMax = $now->format("Y-m-d");
                break;
            case "yesterday":
                $yesterday = DateFactory::createDateFromStr("now -1 day");
                $dateMin = $yesterday->format("Y-m-d");
                $dateMax = $yesterday->format("Y-m-d");
                break;
            case "week":
                $day = date('w');
                if ($day == 0) {
                    $day = 7;
                }
                $dateMin = date("Y-m-d", strtotime('-' . ($day - 1) . ' days'));
                $dateMax = date("Y-m-d", strtotime('+' . (7 - $day) . ' days'));
                break;
            case "month":
                $monthFirst = DateFactory::createDateFromStr("first day of this month");
                $monthLast = DateFactory::createDateFromStr("last day of this month");
                $dateMin = $monthFirst->format("Y-m-d");
                $dateMax = $monthLast->format("Y-m-d");
                break;
            default:
                $now = DateFactory::createDateFromStr("now");
                $dateMin = $now->format("Y-m-d");
                $dateMax = $now->format("Y-m-d");
        }

        return [
            'dateMin' => $dateMin,
            'dateMax' => $dateMax
        ];
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeInsert(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Mesures');

        $sensorId = $request->getData("id_sensor");
        $sensorEntity = $manager->getSensor($sensorId)[0];
        $temperature = $request->getData("temperature");
        $hygrometrie = $request->getData("hygrometrie");

        $t_min_lim = -20;
        $t_max_lim = 50;
        $h_min_lim = 0;
        $h_max_lim = 100;

        if ($sensorEntity->categorie() == "teleinfo") {
            $t_min_lim = 0;
            $t_max_lim = 1000000;
            $h_min_lim = 0;
            $h_max_lim = 10000;
        }

        if (($temperature < $t_min_lim || $temperature > $t_max_lim)
            && ($hygrometrie < $h_min_lim || $hygrometrie > $h_max_lim)) {
            $this->page->addVar('ajoutmesure', "limites de mesure dépassées!");
        }

        $derniereValeur1 = $sensorEntity->valeur1();
        $sensorEntity->setValeur1($temperature);
        $sensorEntity->setValeur2($hygrometrie);

        $diff = abs($derniereValeur1 - $temperature);

        if ($diff > 0) {
            $mesure = new Mesure(['id_sensor' => $sensorId,
                'temperature' => $temperature,
                'hygrometrie' => $hygrometrie]);
            $this->page->addVar('ajoutmesure', $manager->addWithSensorId($mesure));
        } else {
            $this->page->addVar('ajoutmesure', 0);
        }

        $manager->sensorActivityUpdate($sensorEntity, 1);

        $dateMinFull = DateFactory::createDateFromStr("now");
        $dateMaxFull = DateFactory::createDateFromStr("now");

        $dateMin = $dateMinFull->format('Y-m-d');
        $dateMax = $dateMaxFull->format('Y-m-d');

        $file = $sensorId . '-' . $dateMin . '-' . $dateMax;
        $this->cache()->setDataPath($file);
        $this->cache()->deleteFile();
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeSensorStruct(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Mesures');
        $radioid = $request->getData("radioid");
        $sensor = $manager->getSensor($radioid);
        $this->page->addVar('sensor', $sensor);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeSensors(HTTPRequest $request)
    {
        $categorie = $request->getData("categorie");
        if ($categorie === null) {
            $categorie = "";
        }
        $manager = $this->managers->getManagerOf('Mesures');
        $sensors = $manager->getSensors($categorie);
        foreach ($sensors as $sensor) {
            $this->checkSensorActivity($sensor->radioid());
        }
        $this->page->addVar('sensors', $sensors);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeUpdate(HTTPRequest $request)
    {
        $this->processForm($request);
        $this->page->addVar('title', 'Modification d\'une mesure');
    }

    /**
     * @param string $radioid
     */
    public function checkSensorActivity($radioid)
    {
        $manager = $this->managers->getManagerOf('Mesures');
        $sensorEntity = $manager->getSensor($radioid)[0];
        $minutes = DateFactory::diffMinutesFromStr("now", $sensorEntity->releve());
        if ($minutes >= 10) {
            $manager->sensorActivityUpdate($sensorEntity, 0);
        }
    }
}
