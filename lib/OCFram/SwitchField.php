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

    /** @var string */
    protected $leftText;

    /** @var string */
    protected $rightText;


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
                'value' => $this->value,
                'leftText' => $this->leftText,
                'rightText' => $this->rightText
            ]
        );

        $switchButtonHtml = $switchButton->getHtml();

        return <<<HTML
        <div class="row">
            $switchButtonHtml
        </div>
HTML;

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

    /**
     * @param string $leftText
     * @return \OCFram\SwitchField
     */
    public function setLeftText(string $leftText)
    {
        $this->leftText = $leftText;

        return $this;
    }

    /**
     * @param string $rightText
     * @return \OCFram\SwitchField
     */
    public function setRightText(string $rightText)
    {
        $this->rightText = $rightText;

        return $this;
    }
}
