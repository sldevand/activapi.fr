<?php

namespace App\Backend\Modules\Command;

use Entity\Actionneur;
use Model\Scenario\ActionManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;
use SFram\Commands\NodeActivator;

/**
 * Class CommandController
 * @package App\Backend\Modules\Command
 */
class CommandController extends BackController
{
    /** @var NodeActivator $nodeActivator */
    protected $nodeActivator;

    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        $config = $this->app()->config()->getVars();
        $this->nodeActivator = new NodeActivator($config);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeToggle(HTTPRequest $request)
    {
        $status = $request->getData('status');
        if (empty($status)) {
            return $this->page()->addVar('output', ['error'=>'No status found']);
        }
        $output = $this->nodeActivator->toggle($status);
        $this->page()->addVar('output', $output);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeGetStatus(HTTPRequest $request)
    {
        $output = $this->nodeActivator->getStatus();
        $this->page()->addVar('output', $output);
    }
}
