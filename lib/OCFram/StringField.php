<?php

namespace OCFram;

/**
 * Class StringField
 * @package OCFram
 */
class StringField extends Field
{
    /**
     * @var int $maxLength
     */
    protected $maxLength;

    /**
     * @var string $readonly
     */
    protected $readonly;

    /**
     * @return mixed|string
     */
    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= $this->errorMessage . '<br />';
        }

        if (!empty($this->label)) {
            $widget .= '<label for="' . $this->id . '">' . $this->label . '</label>';
        }

        $widget .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"';

        if (isset($this->value)) {
            $widget .= ' value="' . htmlspecialchars($this->value) . '"';
        }

        if (!empty($this->maxLength)) {
            $widget .= ' maxlength="' . $this->maxLength . '"';
        }

        if (!empty($this->readonly)) {
            $widget .= 'readonly ';
        }

        if (!empty($this->hidden)) {
            $widget .= 'hidden ';
        }

        if (!empty($this->required)) {
            $widget .= 'required';
        }

        return $widget .= ' />';
    }

    /**
     * @param int $maxLength
     * @return StringField
     */
    public function setMaxLength($maxLength)
    {
        $maxLength = (int)$maxLength;

        if ($maxLength > 0) {
            $this->maxLength = $maxLength;
        } else {
            throw new \RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
        }

        return $this;
    }

    /**
     * @param string $readonly
     * @return StringField
     */
    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;

        return $this;
    }
}
