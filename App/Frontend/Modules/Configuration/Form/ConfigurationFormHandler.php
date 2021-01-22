<?php

namespace  App\Frontend\Modules\Configuration\Form;

use OCFram\Entity;
use OCFram\Form;
use OCFram\FormHandler;
use OCFram\HTTPRequest;
use OCFram\Manager;

/**
 * Class ConfigurationFormHandler
 * @package App\Frontend\Modules\Configuration\Form
 */
class ConfigurationFormHandler extends FormHandler
{
    /** @var \OCFram\Entity */
    protected $entity;

    /**
     * ConfigurationFormHandler constructor.
     * @param \OCFram\Form $form
     * @param \OCFram\Manager $manager
     * @param \OCFram\HTTPRequest $request
     * @param \OCFram\Entity $entity
     */
    public function __construct(
        Form $form,
        Manager $manager,
        HTTPRequest $request,
        Entity $entity
    ) {
        parent::__construct($form, $manager, $request);
        $this->entity = $entity;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function process()
    {
        if ($this->request->method() !== 'POST' || !$this->form->isValid()) {
            return false;
        }

        $this->manager->save($this->entity);

        return true;
    }
}
