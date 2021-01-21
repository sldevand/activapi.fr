<?php

namespace App\Frontend\Modules\ThermostatPlanif\Block;

use Materialize\Card\Card;
use Materialize\Link\Link;
use Materialize\WidgetFactory;

/**
 * Class PlanifCard
 * @package App\Frontend\Modules\ThermostatPlanif\Block
 */
class PlanifCard
{
    /**
     * @param string $domId
     * @param string $cardTitle
     * @param string $backUrl
     * @param array $thermostatDatas
     * @param array $hideColumns
     * @return \Materialize\Card\Card
     */
    public static function create(
        string $domId,
        string $cardTitle,
        string $backUrl,
        array $thermostatDatas,
        array $hideColumns
    ): Card {
        $card = WidgetFactory::makeCard($domId, $cardTitle);

        $linkDelete = new Link(
            'Supprimer ce Planning',
            $backUrl,
            'delete', 'secondaryTextColor'
        );
        $card->addContent($linkDelete->getHtml());

        $table = WidgetFactory::makeTable($domId, $thermostatDatas, true, $hideColumns);

        return $card->addContent($table->getHtml());
    }
}
