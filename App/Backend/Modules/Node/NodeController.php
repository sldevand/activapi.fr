<?php

namespace App\Backend\Modules\Node;

use DateTime;
use DateTimeZone;
use Entity\Actionneur;
use Entity\Log\Log;
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

    /** @var LogManagerPDO $logManager */
    protected $logManager;

    /**
     * NodeController constructor.
     * @param Application $app
     * @param $module
     * @param $action
     * @throws Exception
     */
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);
        $config = $this->app()->config();
        $this->nodeActivator = new NodeActivator($config);
        $this->logManager = $this->managers->getManagerOf('Log\Log');
    }

    /**
     * @return \OCFram\Page
     * @throws Exception
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
     * @throws Exception
     */
    public function executeGetStatus(HTTPRequest $request)
    {
        $output = $this->nodeActivator->getStatus();
        $this->page()->addVar('output', $output);
    }

    /**
     * @return \OCFram\Page
     * @throws Exception
     */
    public function executeLog(HTTPRequest $request)
    {
        $period = $request->getData('period') ? $request->getData('period') : '';
        list($from, $to) = $this->getPeriod($period);
        $output = ['messages' => $this->logManager->getAll($from, $to)];

        return $this->page()->addVar('output', $output);
    }

    /**
     * @param string $period
     * @return array
     * @throws Exception
     */
    protected function getPeriod($period)
    {
        $date = new DateTime();
        $now = $date->getTimestamp();

        $beginOfDay = strtotime("midnight", $now);
        $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;
        $beginOfyesterday = strtotime('-1 day', $beginOfDay);
        $endOfYesterday   = $beginOfDay - 1;

        return match ($period) {
            'yesterday' => [$beginOfyesterday, $endOfYesterday],
            'week' => [$beginOfyesterday, $now],
            default => [$beginOfDay, $endOfDay],
        };
    }

    /**
     * @return \OCFram\Page
     */
    public function executePostLog(HTTPRequest $request)
    {
        try {
            $this->checkMethod($request, HTTPRequest::POST);
            $jsonPost = $request->getJsonPost();
            $this->checkJsonBodyId($jsonPost);
            $log = new Log($jsonPost);
            $this->logManager->save($log);
            $id = $this->logManager->getLastInserted('log');
            $log->setId($id);
            http_response_code(201);
        } catch (Exception $e) {
            return $this->page->addVar('data', ['error' => $e->getMessage()]);
        }

        return $this->page()->addVar('data', $log);
    }
}
