<?php

namespace SFram;

use DOMDocument;

/**
 * Class GeneralConfig
 * @package SFram
 */
class GeneralConfig
{
    /**
     * @var string $configFile
     */
    protected $configFile;

    /**
     * @var array $vars
     */
    protected $vars = [];

    /**
     * GeneralConfig constructor.
     * @param string $configFile
     * @throws \Exception
     */
    public function __construct($configFile)
    {
        $this->configFile = $configFile;
        $this->vars = $this->getVars();
    }

    /**
     * @param string $var
     * @return mixed|null
     */
    public function get($var)
    {
        if (!isset($this->vars[$var])) {
            return null;
        }

        return $this->vars[$var];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getVars()
    {
        if (!file_exists($this->configFile)) {
            throw new \Exception('No config file found in ' . $this->configFile);
        }

        $vars = [];
        $xml = new DOMDocument();
        $xml->load($this->configFile);
        $elements = $xml->getElementsByTagName('module');
        foreach ($elements as $element) {
            $vars[$element->getAttribute('name')] = $element->getAttribute('version');
        }

        return $vars;
    }
}
