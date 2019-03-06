<?php

namespace Materialize;

/**
 * Class Widget
 * @package Materialize
 */
abstract class Widget
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $wrapper;

    /**
     * Widget constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /**
     * @param array $data
     */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->_id;
    }

    /**
     * @param string $id
     * @return Widget
     */
    public function setId($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * @return string
     */
    abstract public function getHtml();

    /**
     * @param $fileName
     * @param mixed ...$args
     * @return false|string
     */
    public function getBlock($fileName, ...$args)
    {
        ob_start();

        if (file_exists($fileName)) {
            require $fileName;
        }

        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }

    /**
     * @param string $wrapper
     * @return Widget
     */
    public function setWrapper($wrapper)
    {
        $this->wrapper = $wrapper;

        return $this;
    }
}
