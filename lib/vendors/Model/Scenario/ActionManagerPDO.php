<?php

namespace Model\Scenario;

use Entity\Actionneur;
use Entity\Scenario\Action;
use Model\ActionneursManagerPDO;
use Model\ManagerPDO;

/**
 * Class ActionManagerPDO
 * @package Model\Scenario
 */
class ActionManagerPDO extends ManagerPDO
{
    /**
     * @var ActionneursManagerPDO $actionneursManagerPDO
     */
    protected $actionneursManagerPDO;

    /**
     * ActionManagerPDO constructor.
     * @param \PDO $dao
     * @param array $args
     */
    public function __construct(\PDO $dao, $args = [])
    {
        parent::__construct($dao, $args);
        $this->tableName = 'action';
        $this->actionneursManagerPDO = $args['actionneursManagerPDO'];
        $this->entity = new Action();
    }

    /**
     * @param Action $action
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function save($action, $ignoreProperties = [])
    {
        parent::save($action, ['actionneur']);

        return $this->getActionId($action);
    }

    /**
     * @param Action $action
     * @return int|mixed
     * @throws \Exception
     */
    public function getActionId($action)
    {
        if (!$action->id()) {
            return $this->getLastInserted($this->tableName);
        }

        return $action->id();
    }

    /**
     * @param int $id
     * @return Action
     * @throws \Exception
     */
    public function getUnique($id)
    {
        /** @var Action $action */
        $action = parent::getUnique($id);
        if (empty($action)) {
            throw new \Exception('No action found!');
        }

        /** @var Actionneur $actionneur */
        $actionneur = $this->actionneursManagerPDO->getUnique($action->getActionneurId());
        
        if ($actionneur) {
            $action->setActionneur($actionneur);
        }

        return $action;
    }

    /**
     * @param int $id
     * @return Action[]
     * @throws \Exception
     */
    public function getAll($id = null)
    {
        /** @var Action[] $actions */
        $actions = parent::getAll($id);
        foreach ($actions as $key => $action) {
            /** @var Actionneur $actionneur */
            $actionneur = $this->actionneursManagerPDO->getUnique($action->getActionneurId());
            if ($actionneur) {
                $actions[$key]->setActionneur($actionneur);
            }
        }

        return $actions;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getRows()
    {
        return parent::getAll();
    }
}
