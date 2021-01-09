<?php

namespace App\Frontend\Modules\Mesures;

use Helper\Pagination\Data;
use Materialize\Table;
use Materialize\WidgetFactory;
use Model\MesuresManagerPDO;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class MesuresController
 * @package App\Frontend\Modules\Mesures
 */
class MesuresController extends BackController
{

    const PAGE_OFFSET = 3;
    const MAX_MEASURE_COUNT_PER_PAGE = 100;

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        /** @var MesuresManagerPDO $managerMesures */
        $managerMesures = $this->managers->getManagerOf('Mesures');

        $page = $request->getData('page') ?? 1;
        $nDernieresMesures = $request->getData("nbMesures") ?? self::MAX_MEASURE_COUNT_PER_PAGE;

        if ($nDernieresMesures > self::MAX_MEASURE_COUNT_PER_PAGE) {
            $nDernieresMesures = self::MAX_MEASURE_COUNT_PER_PAGE;
        }
        $cards = [];
        $listeMesures = $managerMesures->getList($nDernieresMesures * ($page - 1), $nDernieresMesures);
        $nombreMesures = $managerMesures->getListCount();

        $domId = 'Mesures';
        $table = WidgetFactory::makeTable($domId, $listeMesures);
        $card = WidgetFactory::makeCard($domId, $domId);

        $pagesCount = ceil($nombreMesures / $nDernieresMesures);

        $paginationHelper = new Data($this->app());
        $uri = $paginationHelper->getUri($request);
        $pages = $paginationHelper->getPaginationPages($page, $uri, $nDernieresMesures, $pagesCount);

        $pagination = WidgetFactory::makePagination($pages, $nDernieresMesures, $page, $uri, $pagesCount);

        $card->addContent($pagination->getHtml());
        $card->addContent($this->measuresView($nombreMesures, $nDernieresMesures, $table));

        $cards[] = $card;

        $this->page->addVar('title', 'Gestion des mesures');
        $this->page->addVar('cards', $cards);
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
