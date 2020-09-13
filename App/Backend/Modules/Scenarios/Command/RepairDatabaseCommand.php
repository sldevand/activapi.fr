<?php

namespace App\Backend\Modules\Scenarios\Command;

use App\Backend\Modules\Scenarios\Cron\RepairDatabaseExecutor;
use SFram\Console\BaseCommand;
use Sldevand\Cron\ExecutorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RepairDatabaseCommand
 * @package App\Backend\Modules\Scenarios\Command
 */
class RepairDatabaseCommand extends BaseCommand
{
    protected static $defaultName = 'scenario:db:repair';

    /** @var ExecutorInterface */
    protected $executor;

    /**
     * RepairDatabaseCommand constructor.
     * @param null|string $name
     */
    public function __construct(?string $name = null)
    {

        parent::__construct($name);
        $this->executor = new RepairDatabaseExecutor();
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

        return 0;
    }
}
