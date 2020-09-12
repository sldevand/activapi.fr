<?php

namespace OCFram;

use Materialize\Button\SwitchButton;

/**
 * Class SwitchField
 * @package OCFram
 */
class SwitchField extends Field
{
    /** @var string */
    protected $title;

    /** @var bool */
    protected $checked = false;

    /**
     * @return mixed
     */
    public function buildWidget()
    {
        $switchButton = new SwitchButton(
            [
                'id' => $this->id,
                'title' => $this->title,
                'checked' => $this->checked,
                'name' => $this->name,
                'value' => $this->value
            ]
        );

        return $switchButton->getHtml();
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param bool $checked
     * @return $this
     */
    public function setChecked(bool $checked)
    {
        $this->checked = $checked;

        return $this;
    }
}
