<?php

namespace OCFram;

/**
 * Class Form
 * @package OCFram
 */
class Form
{
    /** @var Entity $entity */
    protected $entity;

    /** @var array $fields */
    protected $fields = [];

    /**
     * Form constructor.
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }

    /**
     * @param Field $field
     * @param null|string $wrapper
     * @return $this
     */
    public function add(Field $field, $wrapper = null)
    {
        $field->setWrapper($wrapper);
        $attr = $field->name();

        if (empty($field->getValue())) {
            $field->setValue($this->entity->$attr());
        }

        $this->fields[] = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function createView()
    {
        $view = '';
        /** @var Field $field */
        foreach ($this->fields as $field) {
            $view .= '<div class="' . $field->getWrapper() . '">';
            $view .= $field->buildWidget();
            $view .= '</div>';
        }

        return $view;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $valid = true;
        foreach ($this->fields as $field) {
            if (!$field->isValid()) {
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * @return Entity
     */
    public function entity()
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     * @return $this
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;

        return $this;
    }
}
