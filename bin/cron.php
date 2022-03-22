<?php

use App\Backend\BackendApplication;
use OCFram\Managers;
use OCFram\PDOFactory;
use Sldevand\Cron\Launcher;

require_once __DIR__ . '/../setup.php';

$app = new BackendApplication();

PDOFactory::setPdoAddress($_ENV['DB_PATH']);
$pdo = PDOFactory::getSqliteConnexion();
$managers = new Managers('PDO',$pdo);
/** @var \Model\Crontab\CrontabManagerPDO $crontabManager */
$crontabManager = $managers->getManagerOf('Crontab\Crontab');
$crontab = $crontabManager->getListLike('executor', 'scenario-', false);
$tempCrontab = \SFram\Utils::objToArray($crontab);
$crontabArray = [];
foreach ($tempCrontab as $key => $item) {
    $item['args'] = json_decode($item['args']);
    foreach ($item['args'] as $key => $arg) {
        if($arg === 'app') {
            unset($item['args'][$key]);
            $item['args']['app'] = $app;
        }
    }
    $crontabArray[$item['name']] = $item;
    unset($crontabArray[$item['name']]['name']);
}
$launcher = new Launcher($crontabArray);
$launcher->launch();
