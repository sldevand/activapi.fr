<?php

namespace SFram;


use Entity\InstalledSetup;
use Model\InstalledSetupManagerPDO;
use SFram\Api\DataSetupInterface;

/**
 * Class DataUpdater
 * @package SFram
 */
class DataUpdater
{
    const SETUP_CLASSES_DIR = APP . 'App/*/Modules/*/Setup/*';
    const SETUP_CLASSES_PATTERN = '/^App\\\\.*\\\\Modules\\\\.*\\\\Setup\\\\.*/';

    /** @var \PDO */
    protected $pdo;

    /**
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        if (!$setupFiles = glob(self::SETUP_CLASSES_DIR)) {
            return 'No setup Files found';
        }
        $originalDeclaredClasses = get_declared_classes();
        foreach ($setupFiles as $setupFile) {
            require_once $setupFile;
        }

        $matchedClasses = [];
        if (!$addedClasses = array_diff(get_declared_classes(), $originalDeclaredClasses)) {
            return 'No classes added to declared classes';
        }
        $installedSetupManager = new InstalledSetupManagerPDO($this->pdo);
        foreach ($addedClasses as $addedClass) {
            preg_match(self::SETUP_CLASSES_PATTERN, $addedClass, $matchedClasses);
            foreach ($matchedClasses as $matchedClass) {
                $instance = new $matchedClass();
                if (!$instance instanceof DataSetupInterface) {
                    throw new \Exception("$matchedClass does not implement \SFram\Api\DataSetupInterface");
                }
                if (!$installedSetupManager->getUniqueBy('className', $matchedClass)) {
                    echo "Installing $matchedClass setup... ";
                    $instance->execute();
                    $installedSetup = new InstalledSetup(['className' => $matchedClass]);
                    $installedSetupManager->save($installedSetup);
                    echo 'OK !' . PHP_EOL;
                }
            }
        }
        if (!$matchedClasses) {
            return 'No data to update';
        }

        return 'Data update successful';
    }

}
