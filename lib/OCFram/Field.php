<?php

namespace OCFram;

/**
 * Class Field
 * @package OCFram
 */
abstract class Field
{
    use Hydrator;

    /**
     * @var string $errorMessage
     */
    protected $errorMessage;

    /**
     * @var string $label
     */
    protected $label;

    /**
     * @var string $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var array $validators
     */
    protected $validators = [];

    /**
     * @var string $value
     */
    protected $value;

    /**
     * @var int $length
     */
    protected $length;

    /**
     * @var string $hidden
     */
    protected $hidden = "";

    /**
     * @var string $required
     */
    protected $required = "false";

    /**
     * @var string $wrapper
     */
    protected $wrapper = 'col s12';

    /**
     * Field constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            $this->hydrate($options);
        }
    }

    /**
     * @return mixed
     */
    abstract public function buildWidget();

    /**
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->validators as $validator) {
            if (!$validator->isValid($this->value)) {
                $this->errorMessage = $validator->errorMessage();
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function label()
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function validators()
    {
        return $this->validators;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function hidden()
    {
        return $this->hidden;
    }

    /**
     * @return string
     */
    public function required()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        if (is_string($label)) {
            $this->label = $label;
        }

        return $this;
    }

    /**
     * @param string $length
     * @return Field
     */
    public function setLength($length)
    {
        $length = (int)$length;

        if ($length > 0) {
            $this->length = $length;
        }

        return $this;
    }

    /**
     * @param string $name
     * @return Field
     */
    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        }

        return $this;
    }

    /**
     * @param array $validators
     * @return $this
     */
    public function setValidators(array $validators)
    {
        foreach ($validators as $validator) {
            if ($validator instanceof Validator && !in_array($validator, $this->validators)) {
                $this->validators[] = $validator;
            }
        }

        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        if (is_string($value)) {
            $this->value = $value;
        }

        return $this;
    }

    /**
     * @param string $hidden
     * @return $this
     */
    public function setHidden($hidden)
    {
        if (is_string($hidden)) {
            $this->hidden = $hidden;
        }

        return $this;
    }

    /**
     * @param string $required
     * @return $this
     */
    public function setRequired($required)
    {
        if (is_string($required)) {
            $this->required = $required;
        }

        return $this;
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
     * @return Field
     */
    public function setWrapper($wrapper)
    {
        if ($wrapper) {
            $this->wrapper = $wrapper;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Field
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


}
