<?php

namespace OCFram;

/**
 * Class FormBuilder
 * @package OCFram
 */
abstract class FormBuilder
{
    /**
     * @var Form $form
     */
    protected $form;

    /** @var array */
    protected $data;

    /**
     * FormBuilder constructor.
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setForm(new Form($entity));
    }

    /**
     * @return \OCFram\Form
     */
    abstract public function build();

    /**
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @return Form
     */
    public function form()
    {
        return $this->form;
    }

    /**
     * @param string|null $key
     * @return mixed|null
     */
    public function getData(?string $key = null)
    {
        if (!$key) {
            return $this->data;
        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return array
     */
    public function addData(string $key, $data)
    {
        $this->data[$key] = $data;

        return $this->data;
    }

    /**
     * @param array $data
     * @return FormBuilder
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
