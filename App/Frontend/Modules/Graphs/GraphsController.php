<?php

namespace App\Frontend\Modules\Graphs;

use Materialize\Button\RaisedButton;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\DateFactory;
use OCFram\HTTPRequest;
use SFram\JSTranslator;


/**
 * Class GraphsController
 * @package App\Frontend\Modules\Graphs
 */
class GraphsController extends BackController
{
    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {

        $graphId = "tempGraph";
        $sensorid = $request->getData("id_sensor");
        $dateMin = $request->getData("dateMin");
        $dateMax = $request->getData("dateMax");
        $today = DateFactory::todayToString();

        if (is_null($dateMin) || $dateMin == "") {
            $dateMin = $today;
        }
        if (is_null($dateMax) || $dateMax == "") {
            $dateMax = $today;
        }

        $manager = $this->managers->getManagerOf('Mesures');
        $listeSensors = $manager->getSensors('thermo');
        $listeThermostat = $manager->getSensors('thermostat');

        $sensorids = [];
        foreach ($listeSensors as $sensor) {
            $sensorids[] = $sensor->radioid();
        }

        foreach ($listeThermostat as $thermostat) {
            $sensorids[] = $thermostat->radioid();
        }

        $tempMin = 10;
        $tempMax = 25;

        if ($sensorid === "sensor24ctn10id3") {
            $tempMin = -5;
            $tempMax = 20;
        }

        $jst = new JSTranslator(
            [
                'apiURL' => $this->getApiUrl(),
                'sensorid' => $sensorid,
                'sensorids' => $sensorids,
                'dateMin' => $dateMin,
                'dateMax' => $dateMax,
                'tempMin' => $tempMin,
                'tempMax' => $tempMax,
                'graphId' => $graphId
            ]
        );

        $buttons = $this->makeButtons($this->provideButtonDatas());
        $period = $this->getSelectedPeriod($dateMin, $dateMax, $today);
        $graphCard = WidgetFactory::makeCard("temperatures", 'Températures');
        $graphCard->addContent($this->periodView($period));
        $graphCard->addContent($this->buttonsView($buttons));
        $graphCard->addContent($this->graphView($graphId));

        $this->page->addVar('title', 'Gestion des Graphs');
        $this->page->addVar('jst', $jst);
        $this->page->addVar('graphId', $graphId);
        $this->page->addVar('graphCard', $graphCard);
    }

    /**
     * @param $buttonDatas
     * @return array
     */
    public function makeButtons($buttonDatas)
    {
        $buttons = [];
        foreach ($buttonDatas as $buttonData) {
            $buttons[] = new RaisedButton($buttonData);
        }

        return $buttons;
    }

    public function provideButtonDatas()
    {
        return [
            [
                'id' => 'yesterday',
                'title' => "Hier",
                'href' => ROOT,
                'extend' => 'col s12'
            ],
            [
                'id' => 'today',
                'title' => "Aujourd'hui",
                'href' => ROOT,
                'extend' => 'col s12'
            ],
            [
                'id' => 'week',
                'title' => "Semaine",
                'href' => ROOT,
                'extend' => 'col s12'
            ],
            [
                'id' => 'month',
                'title' => "Mois",
                'href' => ROOT,
                'extend' => 'col s12'
            ]
        ];
    }

    /**
     * @param $dateMin
     * @param $dateMax
     * @param $today
     * @return string
     */
    public function getSelectedPeriod($dateMin, $dateMax, $today)
    {
        $dateMinFr = DateFactory::toFrDate($dateMin);
        $dateMaxFr = DateFactory::toFrDate($dateMax);

        if ($dateMin !== $dateMax) {
            return "Températures du $dateMinFr au $dateMaxFr";
        }

        if ($dateMin === $today) {
            return "Aujourd'hui";
        }

        return "$dateMinFr";
    }

    /**
     * @param array $buttons
     * @return false|string
     */
    public function buttonsView($buttons)
    {
        return $this->getBlock(MODULES . '/Graphs/Block/buttonsView.phtml', $buttons);
    }

    /**
     * @param string $graphId
     * @return false|string
     */
    public function graphView($graphId)
    {
        return $this->getBlock(MODULES . '/Graphs/Block/graphView.phtml', $graphId);
    }

    /**
     * @param string $period
     * @return false|string
     */
    public function periodView($period)
    {
        return $this->getBlock(MODULES . '/Graphs/Block/periodView.phtml', $period);
    }
}
