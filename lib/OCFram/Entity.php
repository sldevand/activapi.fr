<?php

namespace OCFram;


/**
 * Class Entity
 * @package OCFram
 */
abstract class Entity implements \ArrayAccess, \JsonSerializable
{
    use Hydrator;

    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var array $erreurs
     */
    protected $erreurs = [];

    /**
     * Entity constructor.
     * @param array $donnees
     */
    public function __construct(array $donnees = [])
    {
        if (!empty($donnees)) {
            $this->hydrate($donnees);
        }
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return empty($this->id);
    }

    /**
     * @return array
     */
    public function erreurs()
    {
        return $this->erreurs;
    }

    /**
     * @param $key
     * @param $erreur
     */
    public function addErreur($key, $erreur)
    {
        $this->erreurs[$key] = $erreur;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @param mixed $var
     * @return mixed
     */
    public function offsetGet($var)
    {
        if (isset($this->$var) && is_callable([$this, $var])) {
            return $this->$var();
        }

        return null;
    }

    /**
     * @param mixed $var
     * @param mixed $value
     * @return Entity
     */
    public function offsetSet($var, $value)
    {
        $method = 'set' . ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method])) {
            $this->$method($value);
        }

        return $this;
    }

    /**
     * @param mixed $var
     * @return bool
     */
    public function offsetExists($var)
    {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    /**
     * @param mixed $var
     * @throws \Exception
     */
    public function offsetUnset($var)
    {
        throw new \Exception('Impossible to delete a value');
    }

    /**
     * @return mixed
     */
    abstract public function jsonSerialize();

    /**
     * @return bool
     */
    public function isValid()
    {
        $properties = get_object_vars($this);
        foreach ($properties as $key => $property) {
            if ($key !== "erreurs" && $key !== "id" && !isset($property)) {
                $objClass = new \ReflectionObject($this);
                $this->erreurs["notValid"] = "in object " . $objClass->name . " , " . $key . " is not set";
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function properties()
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function methods()
    {
        $cl = new \ReflectionObject($this);
        $class = $cl->name;
        $array1 = get_class_methods($class);
        if ($parent_class = get_parent_class($class)) {
            $array2 = get_class_methods($parent_class);
            $array3 = array_diff($array1, $array2);
        } else {
            $array3 = $array1;
        }
        return ($array3);
    }
}
