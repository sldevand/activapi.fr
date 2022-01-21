<?php

namespace App\Frontend\Modules\ThermostatPlanif\Block;

use Materialize\Card\Card;
use Materialize\Link\Link;
use Materialize\WidgetFactory;
use OCFram\Block;

/**
 * Class PlanifCard
 * @package App\Frontend\Modules\ThermostatPlanif\Block
 */
class PlanifCard
{
    const TEST_BUTTON_LAYOUT_TEMPLATE = __DIR__ . '/../Block/buttonsLayout.phtml';

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
        $links = [];
        if (isset($urls['back'])) {
            $links['back'] = new Link(
                'Supprimer',
                $urls['back'],
                'delete',
                'secondaryTextColor'
            );
        }

        if (isset($urls['duplicate'])) {
            $links['duplicate'] = new Link(
                'Dupliquer',
                $urls['duplicate'],
                'content_copy',
                'primaryTextDarkColor'
            );
        }

        $buttonsLayout = self::getButtonsLayout($links);

        $card->addContent($buttonsLayout);
        $table = WidgetFactory::makeTable($domId, $thermostatDatas, true, $hideColumns);

        return $card->addContent($table->getHtml());
    }

    /**
     * @param array $links
     * @return string
     */
    protected static function getButtonsLayout(array $links): string
    {
        return Block::getTemplate(self::TEST_BUTTON_LAYOUT_TEMPLATE, $links);
    }
}
