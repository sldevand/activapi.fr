<?php

namespace App\Backend\Modules\Scenarios\Cron;

use Cron\CronExpression;
use Entity\Crontab\Crontab;
use Model\Scenario\ScenarioManagerPDOFactory;
use Model\Scenario\ScenarioSocketIoSender;
use OCFram\Application;
use OCFram\Managers;
use OCFram\PDOFactory;
use Sldevand\Cron\ExecutorInterface;

/**
 * Class ScenariosExecutor
 * @package App\Backend\Modules\Scenarios\Cron
 */
class ScenariosExecutor implements ExecutorInterface
{
    /** @var \OCFram\Managers */
    protected $managers;

    /** @var \Model\Scenario\ScenarioSocketIoSender */
    protected $sender;

    /**
     * ScenariosExecutor constructor.
     * @param array|null $args
     */
    public function __construct(?array $args = null)
    {
        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $this->sender = new ScenarioSocketIoSender($args['app']);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function execute()
    {
        $scenarioManagerFactory = new ScenarioManagerPDOFactory();
        $scenariosManager = $scenarioManagerFactory->getScenariosManager();

        /** @var \Model\Crontab\CrontabManagerPDO $crontabManager */
        $crontabManager = $this->managers->getManagerOf('Crontab\Crontab');
        $crontabScenarios = $crontabManager->getListLike('executor', 'scenario-');

        $sent = false;
        /** @var Crontab $crontabScenario */
        foreach ($crontabScenarios as $crontabScenario) {
            $cron = CronExpression::factory($crontabScenario->getExpression());
            if (!$cron->isDue()) {
                continue;
            }

            $executor = explode('-', $crontabScenario->getExecutor());
            $id = $executor[1];
            $scenario = $scenariosManager->getUnique($id);
            $sent = $this->sender->send($scenario);
        }

        return $sent;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Execute scheduled Scenarios';
    }
}
