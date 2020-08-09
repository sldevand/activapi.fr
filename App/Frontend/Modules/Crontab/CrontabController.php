<?php

namespace App\Frontend\Modules\Crontab;

use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class CrontabController
 * @package App\Frontend\Modules\Crontab
 */
class CrontabController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Crontab');

        $cardContent = 'test';
        $card = WidgetFactory::makeCard('crontab-card', 'Crontab');
        $card->addContent($cardContent);
        $cards[] = $card;


        $this->page->addVar('cards', $cards);
    }
}
