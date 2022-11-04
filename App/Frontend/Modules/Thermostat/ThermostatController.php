<?php

namespace App\Frontend\Modules\Thermostat;

use Helper\Pagination\Data;
use Materialize\Table;
use Materialize\WidgetFactory;
use Model\ThermostatManagerPDO;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class ThermostatController
 * @package App\Frontend\Modules\Thermostat
 */
class ThermostatController extends BackController
{
    const LOGS_COUNT = 100;

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion du Thermostat');

        $thermostats = $this->getThermostats();
        $thermostatTableBlock = $this->getBlock(
            MODULES . '/Thermostat/Block/thermostatTableView.phtml',
            $this->makeThermostatTable($thermostats)
        );
        $rtcBlock = $this->getBlock(MODULES . '/Thermostat/Block/rtcView.phtml', WidgetFactory::makeSpinner("spinner"));
        $thermostatCard = WidgetFactory::makeCard('thermostat-card', 'Thermostat')
            ->addContent($thermostatTableBlock)
            ->addContent($rtcBlock);
        $cards = [];
        $cards[] = $thermostatCard;
        $sensors = $this->managers->getManagerOf('Mesures')->getSensors(['thermostat']);
        $cards[] = $this->makeSensorsWidget($sensors);
        if ($actionneurs = $this->managers->getManagerOf('Actionneurs')->getList("thermostat")) {
            $cards[] = $this->makeActionneurWidget($actionneurs);
        }

        $this->page->addVar('cards', $cards);
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
            $nameObject = $thermostatPlanifManager->getNom($thermostat->planning());
            $name = $nameObject ? $nameObject->nom() : 'Aucun';
            $thermostat->setPlanningName($name);
            $thermostat->setLastTurnOn($thermostatManager->getLastTurnOnLog());
        }

        return $thermostats;
    }

    /**
     * @param $thermostats
     * @param $planifs
     * @return \Materialize\Table
     */
    public function makeThermostatTable($thermostats): Table
    {
        $thermostats = json_decode(json_encode($thermostats), true);
        $tableThermostatDatas = [];
        foreach ($thermostats as $thermostat) {
            $thermostat["mode"] = $thermostat["mode"]["nom"];
            $thermostat["etat"] = $this->iconifyPower($thermostat["etat"] ?? 0);
            $thermostat['pwr'] = $this->iconifyPower($thermostat['pwr'] ?? 0);
            $thermostat['lastPwrOff'] = $thermostat['lastPwrOff'] ?? '';
            $tableThermostatDatas[] = $thermostat;
        }
        $hideColumns = ['id', 'nom', 'modeid', 'planning', 'manuel', 'sensor', 'sensorid', 'temperature', 'hygrometrie', 'interne'];

        return WidgetFactory::makeTable('thermostat-table', $tableThermostatDatas, false, $hideColumns);
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
     * @return \Materialize\Card\Card
     */
    public function makeSensorsWidget($sensors)
    {
        $tableDatas = [];
        $datasSensors = json_decode(json_encode($sensors), true);
        foreach ($datasSensors as $data) {
            $data["actif"] = $this->iconifyResult($data["actif"]);
            $tableDatas[] = $data;
        }
        $tableSensor = WidgetFactory::makeTable('capteur-table', $tableDatas, false);

        return WidgetFactory::makeCard('capteur-card', 'Capteur', $tableSensor->getHtml());
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
        $tableThermostat = WidgetFactory::makeTable('actionneur-table', $actionneur);
        $cardContent = $tableThermostat->getHtml();

        $card = WidgetFactory::makeCard('actionneur-card', 'Actionneur');
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
