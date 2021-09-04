<?php

namespace App\Backend\Modules\Cache\Command\Executor;

use OCFram\Application;

/**
 * Class Flush
 * @package App\Backend\Modules\Cache\Command\Executor
 */
class Flush
{
    /**
     * @param Application $app
     * @return array|false
     * @throws \Exception
     */
    public function execute(Application $app)
    {
        $cacheViewsDirPattern = $app->config()->get('cache_dir') . 'views/*';
        if (!$files = glob($cacheViewsDirPattern)) {
            return [];
        }

        $deletedFiles = [];
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }
            if (unlink($file)) {
                $deletedFiles[] = $file;
            }
        }

        return $deletedFiles;
    }
}
