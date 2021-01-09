<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../setup.php';

use App\Backend\Modules\Scenarios\Command\RepairDatabaseCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new RepairDatabaseCommand());
$application->run();
