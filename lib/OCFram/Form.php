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
     * @return $this
     */
    public function add(Field $field)
    {
        $attr = $field->name();
        $field->setValue($this->entity->$attr());

        $this->fields[] = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function createView()
    {
        $view = '';
        foreach ($this->fields as $field) {
            $view .= $field->buildWidget() . '<br />';
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
