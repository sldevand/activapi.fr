<?php

namespace App\Backend\Modules\Scenarios\Command;

use SFram\Console\BaseCommand;
use Sldevand\Cron\ExecutorInterface;
use \App\Backend\Modules\Cache\Command\FlushCommand;
use Symfony\Component\Console\Input\InputInterface;
use App\Backend\Modules\Cache\Command\Executor\Flush;
use Symfony\Component\Console\Output\OutputInterface;
use App\Backend\Modules\Scenarios\Cron\RepairDatabaseExecutor;

/**
 * Class RepairDatabaseCommand
 * @package App\Backend\Modules\Scenarios\Command
 */
class RepairDatabaseCommand extends BaseCommand
{
    protected static $defaultName = 'scenario:db:repair';

    protected ExecutorInterface $executor;
    protected FlushCommand $flushCommand;

    /**
     * RepairDatabaseCommand constructor.
     * @param null|string $name
     * @throws \Exception
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->executor = new RepairDatabaseExecutor();
        $this->flushCommand = new FlushCommand();
    }

    protected function configure()
    {
        $this->setDescription('Repair Scenario Module orphan relations between tables');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executor->execute();
        $this->flushCommand->execute($input, $output);

        return 0;
    }
}
