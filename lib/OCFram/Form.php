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

    /** @var \OCFram\Field[] $fields */
    protected $fields = [];

    /** @var array $widgets */
    protected $widgets = [];

    /** @var string */
    protected $action = '';

    /** @var string */
    protected $id = '';

    /** @var bool */
    protected $ajax = false;

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
     * @return $this
     */
    public function add(Field $field)
    {
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
     * @return string
     */
    public function createView()
    {
        $view = '';
        foreach ($this->fields as $field) {
            if ($field->getSeparator() === 'top') {
                $view .= '<div class="row"></div>';
            }
            $view .= '<div class="' . $field->getWrapper() . '">';
            $view .= $field->buildWidget();
            $view .= '</div>';
            $view .= $this->createWidgetView($field);
            if ($field->getSeparator() === 'bottom') {
                $view .= '<div class="row"></div>';
            }
        }

        return $view;
    }

    /**
     * @param Field $field
     * @return string
     */
    public function createWidgetView($field)
    {
        $view = '';
        foreach ($this->widgets as $widget) {
            if (!empty($widget['after']) && $widget['after'] === $field->id()) {
                $view .= '<label for="' . $widget['widget']->id() . '">supprimer</label>';
                $view .= $widget['widget']->getHtml();
                return $view;
            }
        }

        return '';
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
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Form
     */
    public function setAction(string $action): Form
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Form
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return $this->ajax;
    }

    /**
     * @param bool $ajax
     * @return Form
     */
    public function setAjax(bool $ajax)
    {
        $this->ajax = $ajax;

        return $this;
    }

    /**
     * @return \OCFram\Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
