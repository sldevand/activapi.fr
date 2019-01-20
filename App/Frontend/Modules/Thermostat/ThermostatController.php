<?php

namespace App\Frontend\Modules\Thermostat;

use Entity\ThermostatMode;
use Entity\ThermostatPlanif;
use FormBuilder\ThermostatModesFormBuilder;
use FormBuilder\ThermostatPlanifFormBuilder;
use FormBuilder\ThermostatPlanifNameFormBuilder;
use Materialize\FloatingActionButton;
use Materialize\Link;
use Materialize\Table;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\DateFactory;
use OCFram\FormHandler;
use OCFram\HTTPRequest;

/**
 * Class ThermostatController
 * @package App\Frontend\Modules\Thermostat
 */
class ThermostatController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion du Thermostat');

        $managerThermostats = $this->managers->getManagerOf('Thermostat');
        $managerThermostatPlanif = $this->managers->getManagerOf('ThermostatPlanif');
        $managerActionneurs = $this->managers->getManagerOf('Actionneurs');
        $managerSensors = $this->managers->getManagerOf('Mesures');

        $thermostats = $managerThermostats->getList();
        $planifs = $managerThermostatPlanif->getList(1);

        $sensors = $managerSensors->getSensors("thermostat");
        $sensorsTemoin = $managerSensors->getSensors("thermo");
        $actionneurs = $managerActionneurs->getList("thermostat");

        $sensorTemoin = null;

        foreach ($sensorsTemoin as $key => $sensor) {
            if ($sensor['id'] == $thermostats[0]['sensorid']) $sensorTemoin = $sensor;
        }
        $cards = [];

        foreach ($thermostats as $thermostat) {
            $name = "Aucun";
            if ($thermostat->planning() > 0) {
                $name = $managerThermostatPlanif->getNom($thermostat->planning())->nom();
            }
            $thermostat->setPlanningName($name);
        }

        $cards[] = $this->makeThermostatWidget($thermostats, $planifs);
        $cards[] = $this->makeSensorsWidget($sensors, $sensorTemoin);
        if ($actionneurs) {
            $cards[] = $this->makeActionneurWidget($actionneurs);
        }

        $this->page->addVar('cards', $cards);
    }

    /**
     * @param $thermostats
     * @param $planifs
     * @return \Materialize\Card\Card
     */
    public function makeThermostatWidget($thermostats, $planifs)
    {
        $thermostats = json_decode(json_encode($thermostats), TRUE);

        $domId = 'Thermostat';
        $tableThermostatDatas = [];
        $hideColumns = ['id', 'nom', 'modeid', 'planning', 'manuel', 'sensor', 'sensorid'];


        foreach ($thermostats as $key => $thermostat) {
            $thermostat["mode"] = $thermostat["mode"]["nom"];
            $thermostat["etat"] = $this->iconifyPower($thermostat["etat"]);


            $tableThermostatDatas[] = $thermostat;
        }

        $tableThermostat = WidgetFactory::makeTable($domId, $tableThermostatDatas, false, $hideColumns);
        $cardContent = $tableThermostat->getHtml();
        $card = WidgetFactory::makeCard($domId, 'Thermostat');
        $card->addContent($cardContent);

        return $card;
    }

    /**
     * @param $state
     * @return string
     */
    public function iconifyPower($state)
    {
        return $this->getBlock(MODULES . '/Thermostat/Block/powerView.phtml', $state);
    }

    /**
     * @param $sensors
     * @param $sensorTemoin
     * @return \Materialize\Card\Card
     */
    public function makeSensorsWidget($sensors, $sensorTemoin)
    {
        $datasSensors = json_decode(json_encode($sensors), true);
        $datasSensorsTemoin = json_decode(json_encode($sensorTemoin), true);
        $domId = 'Capteurs';

        $tableDatas = [];
        $tableDatasSensorTemoin = [];

        foreach ($datasSensors as $key => $data) {
            $data["actif"] = $this->iconifyResult($data["actif"]);
            $tableDatas[] = $data;
        }
        $datasSensorsTemoin["actif"] = $this->iconifyResult($datasSensorsTemoin["actif"]);
        $tableDatasSensorTemoin[] = $datasSensorsTemoin;


        $tableSensor = WidgetFactory::makeTable($domId, $tableDatas, false);
        $tableSensorTemoin = WidgetFactory::makeTable($domId, $tableDatasSensorTemoin, false);


        $cardContent = '<div class="flow-text">Capteur Interne</div>';
        $cardContent .= $tableSensor->getHtml();

        $cardContent .= '<div class="flow-text">Capteur Témoin</div>';
        $cardContent .= $tableSensorTemoin->getHtml();

        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($cardContent);

        return $card;
    }

    /**
     * @param $state
     * @return string
     */
    public function iconifyResult($state)
    {
        if ($state == 1) {
            $icon = "check";
            $color = "teal-text";
        } else {
            $icon = "cancel";
            $color = "secondaryTextColor";
        }

        $html = '<i class="material-icons ' . $color . ' ">' . $icon . '</i>';

        return $html;
    }

    /**
     * @param $actionneur
     * @return \Materialize\Card\Card
     */
    public function makeActionneurWidget($actionneur)
    {
        $domId = 'Actionneur';

        $tableThermostat = WidgetFactory::makeTable($domId, $actionneur);
        $cardContent = $tableThermostat->getHtml();

        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($cardContent);

        return $card;
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeLog(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Thermostat Log');
        $domId = 'Log';

        $nDerniersLogs = 10;
        if ($request->getExists("nbLogs")) {
            $nDerniersLogs = $request->getData("nbLogs");
            if ($nDerniersLogs > 100) {
                $nDerniersLogs = 100;
            }
        }

        $cards = [];
        $manager = $this->managers->getManagerOf('Thermostat');

        $logList = $manager->getLogList(0, $nDerniersLogs);
        $logListTab = json_decode(json_encode($logList), true);
        $tableDatas = [];

        foreach ($logListTab as $key => $log) {
            $log["etat"] = $this->iconifyPower($log["etat"]);
            $tableDatas[] = $log;
        }

        $nbLogs = $manager->countLogs();
        $table = WidgetFactory::makeTable($domId, $tableDatas);
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($this->logsView($nbLogs, $nDerniersLogs, $table));
        $cards[] = $card;

        $this->page->addVar('cards', $cards);
        $this->page->addVar('nDerniersLogs', $nDerniersLogs);
    }

    /**
     * @param int $nbLogs
     * @param int $nbDerniersLogs
     * @param Table $table
     * @return false|string
     */
    public function logsView($nbLogs, $nbDerniersLogs, $table)
    {
        return $this->getBlock(
            MODULES . '/Thermostat/Block/logsView.phtml',
            $nbLogs,
            $nbDerniersLogs,
            $table
        );
    }
}
