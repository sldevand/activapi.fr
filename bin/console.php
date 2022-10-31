<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../setup.php';

use App\Backend\Modules\Cache\Command\FlushCommand;
use App\Backend\Modules\Mesures\Command\CleanOrphanMesuresCommand;
use App\Backend\Modules\Node\Command\PurgeOldLogsCommand;
use App\Backend\Modules\Scenarios\Command\RepairDatabaseCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new RepairDatabaseCommand());
$application->add(new CleanOrphanMesuresCommand());
$application->add(new FlushCommand());
$application->add(new PurgeOldLogsCommand());
$application->run();
