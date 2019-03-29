<?php

namespace App\Backend\Modules\Sequences;

use App\Backend\Modules\AbstractScenarioManagersController;
use Entity\Scenario\Sequence;
use OCFram\Application;
use OCFram\RestInterface;

/**
 * Class SequencesController
 * @package App\Backend\Modules\Scenarios\Views
 */
class SequencesController extends AbstractScenarioManagersController implements RestInterface
{
    /**
     * ScenariosController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);

        $this->manager = $this->getSequencesManager();
        $this->entity = Sequence::class;
    }
}
