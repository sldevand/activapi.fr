<?php

namespace Entity;

use OCFram\Entity;

/**
 * Class Actionneur
 * @package Entity
 */
class Actionneur extends Entity
{
    /**
     * @var string $nom
     */
    protected $nom;

    /**
     * @var string $module
     */
    protected $module;

    /**
     * @var string $protocole
     */
    protected $protocole;

    /**
     * @var string $adresse
     */
    protected $adresse;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var string $radioid
     */
    protected $radioid;

    /**
     * @var string $etat
     */
    protected $etat;

    /**
     * @var string $categorie
     */
    protected $categorie;

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Actionneur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param string $module
     * @return Actionneur
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocole()
    {
        return $this->protocole;
    }

    /**
     * @param string $protocole
     * @return Actionneur
     */
    public function setProtocole($protocole)
    {
        $this->protocole = $protocole;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     * @return Actionneur
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Actionneur
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getRadioid()
    {
        return $this->radioid;
    }

    /**
     * @param string $radioid
     * @return Actionneur
     */
    public function setRadioid($radioid)
    {
        $this->radioid = $radioid;
        return $this;
    }

    /**
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     * @return Actionneur
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     * @return Actionneur
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {

        return array(
            'id' => $this->id(),
            'nom' => $this->nom,
            'module' => $this->module,
            'protocole' => $this->protocole,
            'adresse' => $this->adresse,
            'type' => $this->type,
            'radioid' => $this->radioid,
            'etat' => $this->etat,
            'categorie' => $this->categorie
        );
    }
}
