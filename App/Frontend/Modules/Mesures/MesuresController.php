<?php

namespace App\Frontend\Modules\Mesures;

use Debug\Log;
use Entity\Mesure;
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
        $nombreMesures = $managerMesures->getListCount();
        $startPage = (int)(($nombreMesures / $nDernieresMesures) - ($page - 1)) * $nDernieresMesures;
        $cards = [];
        $listeMesures = $managerMesures->getList($startPage , $nDernieresMesures);
        usort($listeMesures, [self::class, "sortMesureByHorodatage"]);

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

    /**
     * @param \Entity\Mesure $mesure
     * @param \Entity\Mesure $mesureAfter
     * @return int
     */
    public function sortMesureByHorodatage(Mesure $mesure, Mesure $mesureAfter)
    {
        if ($mesure->horodatage() === $mesureAfter->horodatage()) {
            return 0;
        }

        return ($mesure->horodatage() > $mesureAfter->horodatage()) ? -1 : 1;
    }
}
