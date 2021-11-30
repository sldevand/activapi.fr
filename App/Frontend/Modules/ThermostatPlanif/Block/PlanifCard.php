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
     * @param array $urls
     * @param array $thermostatDatas
     * @param array $hideColumns
     * @return \Materialize\Card\Card
     */
    public static function create(
        string $domId,
        string $cardTitle,
        array $urls,
        array $thermostatDatas,
        array $hideColumns
    ): Card {
        $card = WidgetFactory::makeCard($domId, $cardTitle);

        if (isset($urls['back'])) {
            $linkDelete = new Link(
                'Supprimer ce Planning',
                $urls['back'],
                'delete',
                'secondaryTextColor'
            );
            $card->addContent($linkDelete->getHtml());
        }

        if (isset($urls['duplicate'])) {
            $linkDuplicate = new Link(
                'Dupliquer',
                $urls['duplicate'],
                'content_copy',
                'primaryTextDarkColor'
            );
            $card->addContent($linkDuplicate->getHtml());
        }

        $table = WidgetFactory::makeTable($domId, $thermostatDatas, true, $hideColumns);

        return $card->addContent($table->getHtml());
    }
}
