<?php

namespace App\Frontend\Modules\Mesures;

use Materialize\Table;
use Materialize\WidgetFactory;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class MesuresController
 * @package App\Frontend\Modules\Mesures
 */
class MesuresController extends BackController
{
    /**
     * @param HTTPRequest $request
     */
    public function executeIndex(HTTPRequest $request)
    {
        $managerMesures = $this->managers->getManagerOf('Mesures');
        $nDernieresMesures = 10;

        if ($request->getExists("nbMesures")) {
            $nDernieresMesures = $request->getData("nbMesures");

            if ($nDernieresMesures > 100) {
                $nDernieresMesures = 100;
            }
        }

        $cards = [];
        $listeMesures = $managerMesures->getList(0, $nDernieresMesures);
        $nombreMesures = $managerMesures->count();
        $cards[] = $this->makeMesuresWidget($listeMesures, $nombreMesures, $nDernieresMesures);

        $this->page->addVar('title', 'Gestion des mesures');
        $this->page->addVar('cards', $cards);
    }

    /**
     * @param $listeMesures
     * @param $nbMesures
     * @param $nDernieresMesures
     * @return \Materialize\Card\Card
     */
    public function makeMesuresWidget($listeMesures, $nbMesures, $nDernieresMesures)
    {
        $domId = 'Mesures';
        $table = WidgetFactory::makeTable($domId, $listeMesures);
        $card = WidgetFactory::makeCard($domId, $domId);
        $card->addContent($this->measuresView($nbMesures, $nDernieresMesures, $table));

        return $card;
    }

    /**
     * @param int $nbMesures
     * @param int $nDernieresMesures
     * @param Table $table
     * @return false|string
     */
    public function measuresView($nbMesures, $nDernieresMesures, $table)
    {
        return $this->getBlock(
            MODULES . '/Mesures/Block/measuresView.phtml',
            $nbMesures,
            $nDernieresMesures,
            $table
        );
    }
}
