<?php

namespace OCFram;

/**
 * Class FormHandler
 * @package OCFram
 */
class FormHandler
{
    /**
     * @var \OCFram\Form $form
     */
    protected $form;

    /**
     * @var \OCFram\Manager $manager
     */
    protected $manager;

    /**
     * @var \OCFram\HTTPRequest $request
     */
    protected $request;

    /**
     * FormHandler constructor.
     * @param Form $form
     * @param Manager $manager
     * @param HTTPRequest $request
     */
    public function __construct(Form $form, Manager $manager, HTTPRequest $request)
    {
        $this->setForm($form);
        $this->setManager($manager);
        $this->setRequest($request);
    }

    /**
     * @return bool
     */
    public function process()
    {
        if ($this->request->method() !== 'POST' || !$this->form->isValid()) {
            return false;
        }
        $this->manager->save($this->form->entity());

        return true;
    }

    /**
     * @param Form $form
     * @return $this
     */
    public function setForm(Form $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @param Manager $manager
     * @return $this
     */
    public function setManager(Manager $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @param HTTPRequest $request
     * @return $this
     */
    public function setRequest(HTTPRequest $request)
    {
        $this->request = $request;

        return $this;
    }
}
