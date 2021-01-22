<?php

namespace App\Frontend\Modules\ThermostatPlanif\Block;

use Materialize\Link\Link;
use Materialize\WidgetFactory;
use OCFram\DateFactory;

/**
 * Class PlanifCardList
 * @package App\Frontend\Modules\ThermostatPlanif\Block
 */
class PlanifCardList
{
    /** @var string */
    protected $baseAddress;

    /**
     * PlanifCardList constructor.
     * @param $baseAddress
     */
    public function __construct($baseAddress)
    {
        $this->baseAddress = $baseAddress;
    }

    /**
     * @param array $thermostatPlanningsContainer
     * @param array $hideColumns
     * @return array
     */
    public function create(
        array $thermostatPlanningsContainer,
        array $hideColumns
    ) {
        $cards = [];
        foreach ($thermostatPlanningsContainer as $thermostatPlannings) {
            $thermostatDatas = [];
            foreach ($thermostatPlannings as $thermostatPlanningObj) {
                $thermostatPlanning = json_decode(json_encode($thermostatPlanningObj), true);
                $thermostatDatas[] = $this->prepareDataForTable($thermostatPlanning);
            }

            $domId = current($thermostatPlannings)["nom"]["nom"];
            $cardTitle = 'Thermostat : Planning  ' . $domId;
            $backUrl = $this->baseAddress . "thermostat-planif-delete-" . $thermostatPlanning["nomid"];
            $cards[] = PlanifCard::create($domId, $cardTitle, $backUrl, $thermostatDatas, $hideColumns);;
        }

        if (empty($cards)) {
            $table = WidgetFactory::makeTable('no-data', []);
            $card = WidgetFactory::makeCard('card-no-data', 'Planification');
            $card->addContent($table->getHtml());
            $cards [] = $card;
        }

        return $cards;
    }

    /**
     * @param $thermostatPlanning
     * @return mixed
     */
    protected function prepareDataForTable($thermostatPlanning)
    {
        $thermostatPlanning["jour"] = DateFactory::toStrDay($thermostatPlanning['jour']);
        $thermostatPlanning["mode"] = $thermostatPlanning["mode"]["nom"];
        $thermostatPlanning["defaultMode"] = $thermostatPlanning["defaultMode"]["nom"];
        $linkEdit = new Link(
            '',
            $this->baseAddress . "thermostat-planif-edit-" . $thermostatPlanning["id"],
            'edit',
            'primaryTextColor'
        );
        $thermostatPlanning["editer"] = $linkEdit->getHtmlForTable();

        return $thermostatPlanning;
    }

}
