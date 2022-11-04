<?php

namespace App\Backend\Modules\Node\Command;

use App\Backend\Modules\Node\Cron\PurgeOldExecutor;
use SFram\Console\BaseCommand;
use Sldevand\Cron\ExecutorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeOldLogsCommand extends BaseCommand
{
    protected static $defaultName = 'node:log:purge';

    /** @var ExecutorInterface */
    protected $executor;

    /**
     * @param string|null $name
     * @throws \Exception
     */
    public function __construct(?string $name = null)
    {
        $this->executor = new PurgeOldExecutor();
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription($this->executor->getDescription());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->executor->execute();

        return 0;
    }
}
