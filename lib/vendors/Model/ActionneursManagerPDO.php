<?php

namespace Model;

use Entity\Actionneur;

/**
 * Class ActionneursManagerPDO
 * @package Model
 */
class ActionneursManagerPDO extends ManagerPDO
{
    /**
     * ActionneursManagerPDO constructor.
     * @param \PDO $dao
     */
    public function __construct(\PDO $dao)
    {
        parent::__construct($dao);
        $this->tableName = 'actionneurs';
    }

    /**
     * @param string|null $categorie
     * @param array|null $in
     * @return Actionneur[]
     * @throws \Exception
     */
    public function getList($categorie = null, $in = null)
    {
        $sql = "SELECT * FROM $this->tableName";

        if (!empty($categorie)) {
            $sql .= ' WHERE categorie = :categorie';
        }

        if (!empty($categorie) && !empty($in)) {
            $sql .= ' AND';
        }

        if (!empty($in) && !empty($in['field'])) {
            $field = $in['field'];
            $values = $in['values'];
            $sql .= " $field IN ($values)";
        }

        $q = $this->prepare($sql);
        if (!empty($categorie)) {
            $q->bindParam(':categorie', $categorie);
        }
        $q->execute();
        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Actionneur');
        $listeActionneur = $q->fetchAll();
        $q->closeCursor();

        return $listeActionneur;
    }
}
