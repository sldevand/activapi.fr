<?php

namespace SFram;

use App\Frontend\Modules\Configuration\Api\ActionInterface;

/**
 * Class ClassFinder
 * @package SFram
 */
class ClassFinder
{
    /**
     * @param string $className
     * @return array
     */
    public static function getConfigClasses(string $className): array
    {
        $pattern = APP . "App/*/Modules/*/Config/$className.php";
        $files = glob($pattern);

        $classes = [];
        foreach ($files as $file) {
            $pathInfo = pathinfo($file);
            $namespace = explode(APP, $pathInfo['dirname'])[1];
            $filename = $namespace . '/' . $pathInfo['filename'];
            $className = str_replace('/', '\\', $filename);
            if (!class_exists($className) && !$className instanceof ActionInterface) {
                continue;
            }
            $classes[] = $className;
        }

        return $classes;
    }
}
