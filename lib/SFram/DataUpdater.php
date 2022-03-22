<?php

namespace SFram;


use SFram\Api\DataSetupInterface;

/**
 * Class DataUpdater
 * @package SFram
 */
class DataUpdater
{
    const SETUP_CLASSES_DIR = APP . 'App/*/Modules/*/Setup/*';
    const SETUP_CLASSES_PATTERN = '/^App\\\\.*\\\\Modules\\\\.*\\\\Setup\\\\.*/';

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
        $addedClasses = array_diff(get_declared_classes(), $originalDeclaredClasses);
        foreach ($addedClasses as $addedClass) {
            preg_match(self::SETUP_CLASSES_PATTERN, $addedClass, $matchedClasses);
            foreach ($matchedClasses as $matchedClass) {
                $instance = new $matchedClass();
                if (!$instance instanceof DataSetupInterface) {
                    throw new \Exception("$matchedClass does not implement \SFram\Api\DataSetupInterface");
                }
                $instance->execute();
            }
        }
        if (!$matchedClasses) {
            return 'No data to update';
        }

        return 'Data update successful';
    }

}
