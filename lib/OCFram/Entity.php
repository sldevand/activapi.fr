<?php

namespace OCFram;

use SFram\MagicObject;

/**
 * Class Entity
 * @package OCFram
 */
abstract class Entity extends MagicObject implements \ArrayAccess, \JsonSerializable
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
     * @return \OCFram\Entity
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
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
     * @return void
     */
    public function offsetSet($var, $value): void
    {
        $method = 'set' . ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method])) {
            $this->$method($value);
        }
    }

    /**
     * @param mixed $var
     * @return bool
     */
    public function offsetExists($var): bool
    {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    /**
     * @param mixed $var
     * @throws \Exception
     */
    public function offsetUnset($var): void 
    {
        throw new \Exception('Impossible to delete a value');
    }

    /**
     * @param array $ignoreProperties
     * @return bool
     * @throws \Exception
     */
    public function isValid($ignoreProperties = [])
    {
        $properties = get_object_vars($this);
        foreach ($properties as $key => $property) {
            if ($key !== "erreurs" && $key !== "id" && !isset($property) && !in_array($key, $ignoreProperties)) {
                $objClass = new \ReflectionObject($this);
                $this->erreurs["notValid"] = "in object " . $objClass->name . " , " . $key . " is not set";
                throw new \Exception($this->erreurs['notValid']);
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

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $properties = get_class_vars(get_class($this));
        $serialized = [];
        foreach ($properties as $property => $value) {
            $getMethod = $this->getPropertyMethod($property);
            $serialized[$property] = $this->$getMethod();
        }

        return $serialized;
    }
}
