<?php

namespace App\Frontend\Modules\Graphs;

use Materialize\Card\Card;
use Materialize\RaisedButton;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\DateFactory;
use OCFram\HTTPRequest;
use SFram\JSTranslator;
use SFram\OSDetectorFactory;

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
        $this->page->addVar('title', 'Gestion des Graphs');

        $key = OSDetectorFactory::getApiAddressKey();

        $apiBaseAddress = $this->app()->config()->get($key);

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

        $apiURL = $apiBaseAddress . "api/mesures/";

        $tempMin = 10;
        $tempMax = 25;

        if ($sensorid === "sensor24ctn10id3") {
            $tempMin = -5;
            $tempMax = 20;
        }

        //GRAPH CARD CREATION
        $cardTitle = 'Températures';
        $cardContent = '';

        $selectedPeriod = $this->getSelectedPeriod($dateMin, $dateMax, $today);
        $cardContent .= "<div>$selectedPeriod</div>";

        //BUTTONS CREATION
        $buttonsData = $this->provideButtonDatas();
        $buttons = $this->makeButtons($buttonsData);

        $graphId = "tempGraph";
        $cardId = "temperatures";
        $cardContent .= '<canvas id="' . $graphId . '" width=500 height=500></canvas>';
        $graphCard = WidgetFactory::makeCard($cardId, $cardTitle, $cardContent);

        $jst = new JSTranslator(
            [
                'apiURL' => $apiURL,
                'sensorid' => $sensorid,
                'sensorids' => $sensorids,
                'dateMin' => $dateMin,
                'dateMax' => $dateMax,
                'tempMin' => $tempMin,
                'tempMax' => $tempMax,
                'graphId' => $graphId
            ]
        );

        $this->page->addVar('jst', $jst);
        $this->page->addVar('graphId', $graphId);
        $this->page->addVar('graphCard', $graphCard);
        $this->page->addVar('buttons', $buttons);
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
}
