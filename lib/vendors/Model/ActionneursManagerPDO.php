<?php

namespace Model;

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
     * @param string $categorie
     * @return array
     * @throws \Exception
     */
    public function getList($categorie = "")
    {
        $sql = "SELECT * FROM $this->tableName";
        if (!empty($categorie)) {
            $sql .= ' WHERE categorie = :categorie';
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
