<?php

namespace Entity\Configuration;

/**
 * Class ConfigurationFactory
 * @package Entity\Configuration
 */
class ConfigurationFactory
{
    /**
     * @param array $data
     * @return \Entity\Configuration\Configuration
     */
    public static function create($data = [])
    {
        if (empty($data)) {
            $data = [
                'configKey' => '',
                'configValue' => ''
            ];
        }

        return new Configuration($data);
    }
}
