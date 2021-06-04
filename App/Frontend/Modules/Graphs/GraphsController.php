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
        $graphId = "tempGraph";;
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

        if (in_array($dateMin, DateFactory::PERIOD_KEYWORDS)) {
            list($dateMin, $dateMax) = DateFactory::getDateLimits($dateMin);
        }

        $jst = new JSTranslator(
            [
                'apiURL' => $this->getApiUrl(),
                'sensorids' => $sensorids,
                'dateMin' => $dateMin,
                'dateMax' => $dateMax,
                'tempMin' => $tempMin,
                'tempMax' => $tempMax,
                'graphId' => $graphId
            ]
        );

        $buttons = $this->makeButtons($this->provideButtonsData());
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
     * @param array $buttonsData
     * @return array
     */
    public function makeButtons($buttonsData)
    {
        $buttons = [];
        foreach ($buttonsData as $buttonsDatum) {
            $buttons[] = new RaisedButton($buttonsDatum);
        }

        return $buttons;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provideButtonsData()
    {
        $hrefs = [];
        foreach (DateFactory::PERIOD_KEYWORDS as $periodKeyword) {
            $hrefs[$periodKeyword] = DateFactory::getDateLimits($periodKeyword);
        }

        return [
            [
                'id' => 'yesterday',
                'title' => "Hier",
                'href' => $this->createUrl($hrefs['yesterday']['dateMin'], $hrefs['yesterday']['dateMax']),
                'extend' => 'col s12'
            ],
            [
                'id' => 'today',
                'title' => "Aujourd'hui",
                'href' => $this->createUrl($hrefs['today']['dateMin'], $hrefs['today']['dateMax']),
                'extend' => 'col s12'
            ],
            [
                'id' => 'week',
                'title' => "Semaine",
                'href' => $this->createUrl($hrefs['week']['dateMin'], $hrefs['week']['dateMax']),
                'extend' => 'col s12'
            ],
            [
                'id' => 'month',
                'title' => "Mois",
                'href' => $this->createUrl($hrefs['month']['dateMin'], $hrefs['month']['dateMax']),
                'extend' => 'col s12'
            ]
        ];
    }

    /**
     * @param $dateMin
     * @param $dateMax
     * @param $today
     * @return string
     * @throws \Exception
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

    /**
     * @param string $dateMin
     * @param string $dateMax
     * @return string
     */
    public function createUrl(string $dateMin, string $dateMax)
    {
        return "graphs-$dateMin-$dateMax";
    }
}
