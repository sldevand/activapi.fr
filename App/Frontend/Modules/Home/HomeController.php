<?php

namespace App\Frontend\Modules\Home;

use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class HomeController
 * @package App\Frontend\Modules\Accueil
 */
class HomeController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'ActivAPI - Accueil');
        $cards = [];
        $cards[] = $this->makeAccueilWidget();

        $this->page->addVar('cards', $cards);
    }

    /**
     * @return \Materialize\Card\Card
     */
    public function makeAccueilWidget()
    {
        $domId = 'Accueil';
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($this->getHomeView());

        return $card;
    }

    /**
     * @return false|string
     */
    public function getHomeView()
    {
        return $this->getBlock(__DIR__ . '/Block/homeView.phtml');
    }
}
