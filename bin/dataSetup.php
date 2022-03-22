<?php
require_once __DIR__ . '/../setup.php';

$generalConfigFile = __DIR__ . '/../App/etc/config.xml';
$moduleVersionSqlPath = __DIR__ . '/../sql/ModuleVersion-1.0.1.sql';
$sqlScriptsDir = __DIR__ . '/../sql/';

use Model\ModuleVersionManagerPDO;
use OCFram\PDOFactory;
use SFram\GeneralConfig;
use SFram\SchemaUpdater;
use SFram\DataUpdater;

PDOFactory::setPdoAddress($_ENV['DB_PATH']);
$pdo = PDOFactory::getSqliteConnexion();

echo PHP_EOL . 'Beginning of Sql Updates' . PHP_EOL;
try {
    //Schema Setup
    if (file_exists($moduleVersionSqlPath) && $script = file_get_contents($moduleVersionSqlPath)) {
        $pdo->exec($script);
        echo 'Execute ModuleVersion installation script' . PHP_EOL;
    }

    $schemaUpdater = new SchemaUpdater(
        $generalConfig = new GeneralConfig($generalConfigFile),
        $moduleVersionManagerPDO = new ModuleVersionManagerPDO($pdo),
        $sqlScriptsDir
    );

    /** @var \Entity\ModuleVersion[] $updated */
    $updated = $schemaUpdater->execute();
    foreach ($updated as $item) {
        echo $item->getModuleName() . ' is updated to version ' . $item->getVersionNumber() . PHP_EOL;
    }

    if (!$updated) {
        echo 'No Tables to update' . PHP_EOL;
    }

    //Data Setup
    $dataUpdater = new DataUpdater();
    echo $dataUpdater->execute() . PHP_EOL;
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    die;
}
echo 'End of Schema and Data Updates' . PHP_EOL;
