<?php

namespace Entity;

use Exception;
use OCFram\Entity;

/**
 * Class Sensor
 * @package Entity
 */
class Sensor extends Entity
{


    /**
     * @var string $radioid
     */
    protected $radioid;

    /**
     * @var string $releve
     */
    protected $releve;

    /**
     * @var bool $actif
     */
    protected $actif;

    /**
     * @var float $valeur1
     */
    protected $valeur1;

    /**
     * @var float $valeur2
     */
    protected $valeur2;

    /**
     * @var string $nom
     */
    protected $nom;

    /**
     * @var string $categorie
     */
    protected $categorie;

    /**
     * @var string $radioaddress
     */
    protected $radioaddress;

    /**
     * @return string
     */
    public function radioid()
    {
        return $this->radioid;
    }

    /**
     * @return string
     */
    public function releve()
    {
        return $this->releve;
    }

    /**
     * @return bool
     */
    public function actif()
    {
        return $this->actif;
    }

    /**
     * @return float
     */
    public function valeur1()
    {
        return $this->valeur1;
    }

    /**
     * @return float
     */
    public function valeur2()
    {
        return $this->valeur2;
    }

    /**
     * @return string
     */
    public function nom()
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function categorie()
    {
        return $this->categorie;
    }

    /**
     * @return string
     */
    public function radioaddress()
    {
        return $this->radioaddress;
    }

    /**
     * @param string $radioid
     * @return Sensor
     * @throws Exception
     */
    public function setRadioid($radioid)
    {

        if (empty($radioid) || !is_string($radioid)) {
            throw new Exception('radioid invalide!');
        }
        $this->radioid = $radioid;

        return $this;
    }

    /**
     * @param string $releve
     * @return Sensor
     */
    public function setReleve($releve)
    {
        $this->releve = $releve;

        return $this;
    }

    /**
     * @param bool $actif
     * @return Sensor
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @param float $valeur1
     * @return Sensor
     */
    public function setValeur1($valeur1)
    {
        $this->valeur1 = $valeur1;

        return $this;
    }

    /**
     * @param float $valeur2
     * @return Sensor
     */
    public function setValeur2($valeur2)
    {
        $this->valeur2 = $valeur2;

        return $this;
    }

    /**
     * @param string $nom
     * @return Sensor
     * @throws Exception
     */
    public function setNom($nom)
    {
        if (empty($nom) || !is_string($nom)) {
            throw new Exception('nom invalide!');
        }
        $this->nom = $nom;

        return $this;
    }

    /**
     * @param string $categorie
     * @return Sensor
     * @throws Exception
     */
    public function setCategorie($categorie)
    {
        if (empty($categorie) || !is_string($categorie)) {
            throw new Exception('categorie invalide!');
        }

        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @param string $radioaddress
     * @return Sensor
     * @throws Exception
     */
    public function setRadioaddress($radioaddress)
    {
        if (empty($radioaddress) || !is_string($radioaddress)) {
            throw new Exception('radioaddress invalide!');
        }
        $this->radioaddress = $radioaddress;

        return $this;
    }


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id(),
            'radioid' => $this->radioid,
            'releve' => $this->releve,
            'actif' => $this->actif,
            'valeur1' => $this->valeur1,
            'valeur2' => $this->valeur2,
            'nom' => $this->nom,
            'categorie' => $this->categorie,
            'radioaddress' => $this->radioaddress
        );
    }
}
