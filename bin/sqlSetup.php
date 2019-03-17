<?php
require_once __DIR__ . '/../setup.php';

$generalConfigFile = __DIR__ . '/../App/Config/config.xml';
$moduleVersionSqlPath = __DIR__ . '/../sql/ModuleVersion-1.0.1.sql';
$sqlScriptsDir = __DIR__ . '/../sql/';

use App\Backend\BackendApplication;
use Model\ModuleVersionManagerPDO;
use OCFram\Config;
use OCFram\PDOFactory;
use SFram\GeneralConfig;
use SFram\OSDetectorFactory;
use SFram\SchemaUpdater;

OSDetectorFactory::begin();
$key = OSDetectorFactory::getPdoAddressKey();
$config = new Config(new BackendApplication());
PDOFactory::setPdoAddress($config->get($key));
$pdo = PDOFactory::getSqliteConnexion();

echo PHP_EOL . 'Beginning of Sql Updates';
try {
    if (file_exists($moduleVersionSqlPath) && $script = file_get_contents($moduleVersionSqlPath)) {
        $pdo->exec($script);
        echo PHP_EOL . 'Execute ModuleVersion installation script';
    }

    $schemaUpdater = new SchemaUpdater(
        new GeneralConfig($generalConfigFile),
        $moduleVersionManagerPDO = new ModuleVersionManagerPDO($pdo),
        $sqlScriptsDir
    );

    /** @var \Entity\ModuleVersion[] $updated */
    $updated = $schemaUpdater->execute();

    foreach ($updated as $item) {
        echo PHP_EOL . $item->getModuleName() . ' is updated to version ' . $item->getVersionNumber();
    }

    if (!$updated) {
        echo PHP_EOL . 'Nothing to update';
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
echo PHP_EOL . 'End of Sql Updates';
