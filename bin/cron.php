<?php

include __DIR__ . '/../vendor/autoload.php';

$launcher = new \SFram\Cron\Launcher($argv);
$launcher->launch();
