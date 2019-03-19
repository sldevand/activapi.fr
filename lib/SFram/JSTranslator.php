<?php

namespace SFram;

/**
 * Class JSTranslator
 * @package SFram
 */
class JSTranslator
{
    /**
     * @var array $phpVars
     */
    protected $phpVars;

    /**
     * JSTranslator constructor.
     * @param array $phpVars
     * @throws \Exception
     */
    public function __construct($phpVars)
    {
        $this->setPhpVars($phpVars);
    }

    /**
     * @param string $key
     * @param array $phpVar
     */
    public function addVar($key, $phpVar)
    {
        $this->phpVars[$key] = $phpVar;
    }

    /**
     * @param array $phpVars
     * @throws \Exception
     */
    public function setPhpVars($phpVars)
    {
        if (!is_array($phpVars)) {
            throw new \Exception('phpVars is not an array');
        }

        foreach ($phpVars as $key => $value) {
            $this->addVar($key, $value);
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function toVars()
    {
        $jsReturn = '<script>';
        if (empty($this->phpVars)) {
            throw new \Exception('$this->phpVars is null!');
        }

        foreach ($this->phpVars as $key => $phpVar) {
            $jsReturn .= 'var ' . $key . ' = ';
            if (!empty($phpVar) && !is_null($phpVar)) {
                if (is_string($phpVar) || is_numeric($phpVar)) {
                    $jsReturn .= '"' . $phpVar . '";';
                } elseif (is_array($phpVar)) {
                    $jsReturn .= "[];";
                    foreach ($phpVar as $value) {
                        $jsReturn .= $key . '.push("' . $value . '");';
                    }
                }
            } else {
                $jsReturn .= '"' . '";';
            }
        }
        $jsReturn .= '</script>';

        return $jsReturn;
    }
}
