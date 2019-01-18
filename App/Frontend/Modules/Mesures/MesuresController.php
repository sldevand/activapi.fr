<?php

namespace App\Frontend\Modules\Mesures;

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

        $this->page->addVar('title', 'Gestion des mesures');

        $managerMesures = $this->managers->getManagerOf('Mesures');

        $nDernieresMesures = 10;

        if ($request->getData("nbMesures")) {
            if ($request->getData("nbMesures") > 100) {
                $nDernieresMesures = 100;
            } else {
                $nDernieresMesures = $request->getData("nbMesures");
            }
        }

        $cards = [];
        //Mesures
        $listeMesures = $managerMesures->getList(0, $nDernieresMesures);
        $nombreMesures = $managerMesures->count();
        $cards[] = $this->makeMesuresWidget($listeMesures, $nombreMesures, $nDernieresMesures);


        $this->page->addVar('cards', $cards);

    }

    /**
     * @param $listeMesures
     * @param $nbMesures
     * @param $nDernieresMesures
     * @return \Materialize\Card
     */
    public function makeMesuresWidget($listeMesures, $nbMesures, $nDernieresMesures)
    {
        $domId = 'Mesures';

        $table = WidgetFactory::makeTable($domId, $listeMesures);
        $cardContent = '<hr><span>Nombre de mesures : ' . $nbMesures . '</br>';
        $cardContent .= 'Voici la liste des ' . $nDernieresMesures . ' dernières Mesures</span><hr>';
        $cardContent .= $table->getHtml();

        return WidgetFactory::makeCard($domId, $domId, $cardContent);
    }
}
