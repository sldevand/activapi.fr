<?php

namespace App\Backend\Modules\Node;

use Entity\Actionneur;
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
     */
    public function executeLog(HTTPRequest $request)
    {
        $file = $this->app()->config()->get('nodeServerLogPath');
        if (!file_exists($file)) {
            return $this->page()->addVar('output', ['error' => 'No log file found']);
        }
        $read = '';
        $lines = file($file);
        $last = count($lines) - 1;
        for ($i = $last; $i >= 0; $i--) {
            $read .= $lines[$i] . '<br>';
        }

        $output = [
            'message' => $read
        ];

        return $this->page()->addVar('output', $output);
    }
}
