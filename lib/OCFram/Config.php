<?php

namespace OCFram;

use DOMDocument;

/**
 * Class Config
 * @package OCFram
 */
class Config extends ApplicationComponent
{
    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @param $var
     * @return mixed|null
     */
    public function get($var)
    {
        if (!$this->vars) {
            $xml = new DOMDocument();
            $xml->load(__DIR__ . '/../../App/' . $this->app->name() . '/Config/app.xml');

            $elements = $xml->getElementsByTagName('define');

            foreach ($elements as $element) {
                $this->vars[$element->getAttribute('var')] = $element->getAttribute('value');
            }
        }

        if (isset($this->vars[$var])) {
            return $this->vars[$var];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }
}
