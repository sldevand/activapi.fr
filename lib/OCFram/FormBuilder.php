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

    /**
     * FormBuilder constructor.
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setForm(new Form($entity));
    }

    /**
     * @return mixed
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
}
