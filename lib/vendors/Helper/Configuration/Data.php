<?php

namespace Helper\Configuration;

use OCFram\ApplicationComponent;

/**
 * Class Data
 * @package Helper\Configuration
 */
class Data extends ApplicationComponent
{
    /**
     * @return string
     * @throws \Exception
     */
    public function getConfigurationIndexUrl()
    {
        return $this->app->config()->getEnv('BASE_URL') . 'configuration';
    }
}
