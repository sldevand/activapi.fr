<?php

namespace App\Frontend\Modules\Mesures;

use Entity\Mesure;
use Helper\Pagination\Data;
use Materialize\Table;
use Materialize\WidgetFactory;
use Model\MesuresManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;

/**
 * Class MesuresController
 * @package App\Frontend\Modules\Mesures
 */
class MesuresController extends BackController
{
    const DEFAULT_PAGE = 1;
    const PAGE_OFFSET = 3;
    const MAX_MEASURE_COUNT_PER_PAGE = 100;

    /** @var MesuresManagerPDO */
    protected $mesuresManager;

    /**
     * MesuresController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);
        $this->mesuresManager = $this->managers->getManagerOf('Mesures');
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $page = $request->getData('page') ?? self::DEFAULT_PAGE;
        $measuresCount = $request->getData("measuresCount") ?? self::MAX_MEASURE_COUNT_PER_PAGE;
        if ($measuresCount > self::MAX_MEASURE_COUNT_PER_PAGE) {
            $measuresCount = self::MAX_MEASURE_COUNT_PER_PAGE;
        }

        $listCount = $this->mesuresManager->getListCount();
        $startPage = (int)(($listCount / $measuresCount) - ($page - 1)) * $measuresCount;
        $measures = $this->mesuresManager->getList($startPage, $measuresCount);
        usort($measures, [self::class, "sortMesureByHorodatage"]);

        $domId = 'Mesures';
        $card = WidgetFactory::makeCard($domId, $domId);

        //Add pagination to card
        $pagination = $this->makePaginationWidget($listCount, $measuresCount, $request, $page);
        $card->addContent($pagination->getHtml());

        //Add measuresGridView to card
        $table = WidgetFactory::makeTable($domId, $measures);
        $measuresGridView = $this->measuresGridView($listCount, $measuresCount, $table);
        $card->addContent($measuresGridView);

        $cards = [$card];

        $this->page->addVar('title', 'Gestion des mesures');
        $this->page->addVar('cards', $cards);
    }

    /**
     * @param int $listCount
     * @param int $measuresCount
     * @param Table $table
     * @return false|string
     */
    public function measuresGridView($listCount, $measuresCount, $table)
    {
        return $this->getBlock(
            MODULES . '/Mesures/Block/measuresView.phtml',
            $listCount,
            $measuresCount,
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

    /**
     * @param int $listCount
     * @param int $measuresCount
     * @param HTTPRequest $request
     * @param int $page
     * @return \Materialize\Pagination\Pagination
     */
    protected function makePaginationWidget(int $listCount, int $measuresCount, HTTPRequest $request, int $page)
    {
        $pagesCount = ceil($listCount / $measuresCount);
        $paginationHelper = new Data($this->app());
        $uri = $paginationHelper->getUri($request);
        $pages = $paginationHelper->getPaginationPages($page, $uri, $measuresCount, $pagesCount);

        return WidgetFactory::makePagination($pages, $measuresCount, $page, $uri, $pagesCount);
    }
}
