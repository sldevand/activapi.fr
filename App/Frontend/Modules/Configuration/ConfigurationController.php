<?php

namespace App\Frontend\Modules\Configuration;

use Materialize\FormView;
use App\Frontend\Modules\Mailer\Config\View\CardBuilder;
use Helper\Configuration\Data;
use Mailer\Helper\Config;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;
use SFram\ClassFinder;

/**
 * Class ConfigurationController
 * @package App\Frontend\Modules\Configuration
 */
class ConfigurationController extends BackController
{
    use FormView;

    /** @var \Model\Configuration\ConfigurationManagerPDO $manager */
    protected $manager;

    /** @var \Helper\Configuration\Data */
    protected $dataHelper;

    /**
     * ConfigurationController constructor.
     * @param \OCFram\Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(
        Application $app,
        string $module,
        string $action
    ) {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('Configuration\Configuration');
        $this->dataHelper = new Data($app);
    }

    /**
     * @param HTTPRequest $request
     * @throws \Exception
     */
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Configuration Système');

        $cards = [];
        $actionNames = ClassFinder::getConfigClasses('Action');

        /** @var string $action */
        foreach ($actionNames as $actionName) {
            $actionInstance = $this->createAction($actionName);
            $cards[] = $actionInstance->execute($request);
        }

        $this->page->addVar('cards', $cards);
    }

    /**
     * @param string $action
     * @return \App\Frontend\Modules\Configuration\Api\ActionInterface
     */
    protected function createAction(string $action): Api\ActionInterface
    {
        return new $action(
            $this->app(),
            $this->manager,
            $this->dataHelper
        );
    }
}
