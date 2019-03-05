<?php

namespace OCFram;

use Materialize\Widget;

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

    /** @var array $fields */
    protected $widgets = [];

    /**
     * Form constructor.
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }

    /**
     * @param mixed $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
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

        if (!method_exists($this->entity, $attr)) {
            $attr = 'get' . ucfirst($field->name());
        }

        if (empty($field->getValue()) && method_exists($this->entity, $attr)) {
            $field->setValue($this->entity->$attr());
        }

        $this->fields[] = $field;
        return $this;
    }

    /**
     * @param Widget $widget
     * @param null|string $after
     * @param null|string $before
     * @return $this
     */
    public function addWidget(Widget $widget, $before = null, $after = null)
    {
        $this->widgets[$widget->id()] = [
            'widget' => $widget,
            'before' => $before,
            'after' => $after
        ];

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
     * @return mixed
     */
    public function entity()
    {
        return $this->entity;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getWidgets()
    {
        return $this->widgets;
    }
}
