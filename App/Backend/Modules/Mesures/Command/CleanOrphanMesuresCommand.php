<?php

namespace App\Backend\Modules\Mesures\Command;

use App\Backend\Modules\Mesures\Cron\CleanOrphanMesuresExecutor;
use SFram\Console\BaseCommand;
use Sldevand\Cron\ExecutorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CleanOrphanMesuresCommand
 * @package App\Backend\Modules\Mesures\Command
 */
class CleanOrphanMesuresCommand extends BaseCommand
{
    protected static $defaultName = 'mesures:db:removeOrphanRows';

    /** @var ExecutorInterface */
    protected $executor;

    /**
     * CleanOrphanMesuresCommand constructor.
     * @param string|null $name
     * @throws \Exception
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->executor = new CleanOrphanMesuresExecutor();
    }

    protected function configure()
    {
        $this->setDescription('Remove measures with orphan sensor rows');
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
