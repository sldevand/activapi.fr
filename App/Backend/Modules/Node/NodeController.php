<?php

namespace App\Backend\Modules\Node;

use Entity\Actionneur;
use Exception;
use Model\Log\LogManagerPDO;
use Model\Scenario\ActionManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;
use SFram\Commands\NodeActivator;

/**
 * Class NodeController
 * @package App\Backend\Modules\Node
 */
class NodeController extends BackController
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
            return $this->page()->addVar('output', ['error' => 'No status found']);
        }
        $output = $this->nodeActivator->toggle($status);

        return $this->page()->addVar('output', $output);
    }

    /**
     * @param HTTPRequest $request
     */
    public function executeGetStatus(HTTPRequest $request)
    {
        $output = $this->nodeActivator->getStatus();
        $this->page()->addVar('output', $output);
    }

    /**
     * @param HTTPRequest $request
     * @return void
     * @throws Exception
     */
    public function executeLog(HTTPRequest $request)
    {
        /** @var LogManagerPDO $logManager */
        $logManager = $this->managers->getManagerOf('Log\Log');
        $output = ['messages' => $logManager->getAll()];

        return $this->page()->addVar('output', $output);
    }
}
