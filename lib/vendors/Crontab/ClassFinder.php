<?php

namespace Crontab;

/**
 * Class ClassFinder
 * @package Crontab
 */
class ClassFinder
{
    /**
     * @param string $className
     * @return array
     */
    public static function getClasses(): array
    {
        $pattern = APP . "App/*/Modules/*/Cron/*";
        $files = glob($pattern);

        $classes = [];
        foreach ($files as $file) {
            $pathInfo = pathinfo($file);
            $namespace = explode(APP, $pathInfo['dirname'])[1];
            $filename = $namespace . '/' . $pathInfo['filename'];
            $className = str_replace('/', '\\', $filename);
            if (!class_exists($className)) {
                continue;
            }
            $classes[] = '\\' . $className;
        }

        return $classes;
    }
}