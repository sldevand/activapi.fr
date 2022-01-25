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
    const BUTTONS_LAYOUT_TEMPLATE = __DIR__ . '/../Block/buttonsLayout.phtml';
    const TITLE_TEMPLATE = __DIR__ . '/../Block/title.phtml';

    /**
     * @param string $domId
     * @param string $cardTitle
     * @param array $urls
     * @param array $thermostatDatas
     * @param array $hideColumns
     * @param bool $selected
     * @return \Materialize\Card\Card
     */
    public static function create(
        string $domId,
        string $cardTitle,
        array $urls,
        array $thermostatDatas,
        array $hideColumns,
        bool $selected
    ): Card {
        $titleTemplate = self::getTitleTemplate($cardTitle, $selected);
        $card = WidgetFactory::makeCard($domId, $titleTemplate);
        $links = [];
        if (isset($urls['back'])) {
            $links['back'] = new Link(
                'Supprimer',
                $urls['back'],
                'delete',
                'secondaryTextColor'
            );
            $links['back']->setAlign('left');
        }

        if (isset($urls['duplicate'])) {
            $links['duplicate'] = new Link(
                'Dupliquer',
                $urls['duplicate'],
                'content_copy',
                'primaryTextDarkColor'
            );
            $links['duplicate']->setAlign('right');
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
        return Block::getTemplate(self::BUTTONS_LAYOUT_TEMPLATE, $links);
    }

    /**
     * @param string $title
     * @return string
     */
    protected static function getTitleTemplate(string $title, bool $selected): string
    {
        return Block::getTemplate(self::TITLE_TEMPLATE, $title, $selected);
    }
}
