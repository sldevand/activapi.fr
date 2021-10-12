<?php

namespace App\Frontend\Modules\Thermostat;

use Helper\Pagination\Data;
use Materialize\Table;
use Materialize\WidgetFactory;
use Model\ThermostatManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class ThermostatController
 * @package App\Frontend\Modules\Thermostat
 */
class ThermostatController extends BackController
{
    const LOGS_COUNT = 100;

    /** @var \Model\MesuresManagerPDO */
    protected $mesuresManager;

    /**
     * @param \OCFram\Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        $this->mesuresManager = $this->managers->getManagerOf('Mesures');
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion du Thermostat');

        $thermostats = $this->getThermostats();
        $cards = [];
        $cards[] = $this->makeThermostatWidget($thermostats);
        $cards[] = $this->makeSensorsWidget(
            $this->mesuresManager->getSensors(['thermostat']),
            $this->getSensorTemoin($thermostats)
        );

        $managerActionneurs = $this->managers->getManagerOf('Actionneurs');
        if ($actionneurs = $managerActionneurs->getList("thermostat")) {
            $cards[] = $this->makeActionneurWidget($actionneurs);
        }

        $this->page->addVar('cards', $cards);
    }

    /**
     * @param array $thermostats
     * @return mixed|null
     * @throws \Exception
     */
    protected function getSensorTemoin(array $thermostats)
    {
        $sensorsTemoin = $this->mesuresManager->getSensors(['thermo']);
        foreach ($sensorsTemoin as $sensor) {
            if ($sensor['id'] == $thermostats[0]['sensorid']) {
                return $sensor;
            }
        }

        return [];
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getThermostats(): array
    {
        /** @var ThermostatManagerPDO $thermostatManager */
        $thermostatManager = $this->managers->getManagerOf('Thermostat');

        /** @var \Model\ThermostatPlanifManagerPDO $thermostatPlanifManager */
        $thermostatPlanifManager = $this->managers->getManagerOf('ThermostatPlanif');

        /** @var \Entity\Thermostat[] $thermostats */
        $thermostats = $thermostatManager->getList() ?? [];
        foreach ($thermostats as $thermostat) {
            $name = $thermostat->planning() > 0
                ? $thermostatPlanifManager->getNom($thermostat->planning())->nom()
                : "Aucun";

            $thermostat->setPlanningName($name);
            $thermostat->setLastTurnOn($thermostatManager->getLastTurnOnLog());
        }

        return $thermostats;
    }

    /**
     * @param $thermostats
     * @param $planifs
     * @return \Materialize\Card\Card
     */
    public function makeThermostatWidget($thermostats)
    {
        $thermostats = json_decode(json_encode($thermostats), true);

        $domId = 'Thermostat';
        $tableThermostatDatas = [];
        $hideColumns = ['id', 'nom', 'modeid', 'planning', 'manuel', 'sensor', 'sensorid'];


        foreach ($thermostats as $thermostat) {
            $thermostat["mode"] = $thermostat["mode"]["nom"];
            $thermostat["etat"] = $this->iconifyPower($thermostat["etat"] ?? 0);
            $thermostat['pwr'] = $this->iconifyPower($thermostat['pwr'] ?? 0);
            $thermostat['lastPwrOff'] = $thermostat['lastPwrOff'] ?? '';

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
        $tableDatas = [];
        $datasSensors = json_decode(json_encode($sensors), true);
        foreach ($datasSensors as $key => $data) {
            $data["actif"] = $this->iconifyResult($data["actif"]);
            $tableDatas[] = $data;
        }

        $domId = 'Capteurs';
        $tableSensor = WidgetFactory::makeTable($domId, $tableDatas, false);
        $cardContent = '<div class="flow-text">Capteur Interne</div>';
        $cardContent .= $tableSensor->getHtml();

        $datasSensorsTemoin = json_decode(json_encode($sensorTemoin), true);
        $datasSensorsTemoin["actif"] = $this->iconifyResult($datasSensorsTemoin["actif"] ?? 0);
        $tableDatasSensorTemoin = [];
        $tableDatasSensorTemoin[] = $datasSensorsTemoin;
        $tableSensorTemoin = WidgetFactory::makeTable($domId, $tableDatasSensorTemoin, false);
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
     * @throws \Exception
     */
    public function executeLog(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Thermostat Log');
        $domId = 'Log';

        $page = $request->getData('page') ?? 1;
        $logsCount = $request->getData('logsCount') ?? self::LOGS_COUNT;

        if ($logsCount > self::LOGS_COUNT) {
            $logsCount = self::LOGS_COUNT;
        }


        $cards = [];
        /** @var ThermostatManagerPDO $manager */
        $manager = $this->managers->getManagerOf('Thermostat');
        $logList = $manager->getLogList($logsCount * ($page - 1), $logsCount);
        $logListTab = json_decode(json_encode($logList), true);
        $tableDatas = [];

        foreach ($logListTab as $key => $log) {
            $log["etat"] = $this->iconifyPower($log["etat"]);
            $tableDatas[] = $log;
        }

        $nbLogs = $manager->countLogs();
        $thermotatLogUri = $this->getThermostatLogUri($request);
        $pagesCount = ceil($nbLogs / $logsCount);

        $paginationHelper = new Data($this->app());
        $pages = $paginationHelper->getPaginationPages($page, $thermotatLogUri, $logsCount, $pagesCount);
        $pagination = WidgetFactory::makePagination($pages, $logsCount, $page, $thermotatLogUri, $pagesCount);

        $table = WidgetFactory::makeTable($domId, $tableDatas);
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($pagination->getHtml());
        $card->addContent($this->logsView($nbLogs, $logsCount, $table));
        $cards[] = $card;

        $this->page->addVar('cards', $cards);
        $this->page->addVar('nDerniersLogs', $logsCount);
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

    /**
     * @param HTTPRequest $request
     * @return string
     */
    protected function getThermostatLogUri(HTTPRequest $request)
    {
        $thermotatLogUri = explode('-', $request->requestURI());
        while (count($thermotatLogUri) > 2) {
            array_pop($thermotatLogUri);
        }

        return implode('-', $thermotatLogUri);
    }
}
