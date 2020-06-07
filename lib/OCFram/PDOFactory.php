<?php

namespace OCFram;

use PDO;
use PDOException;

/**
 * Class PDOFactory
 * @package OCFram
 */
class PDOFactory
{
    /**
     * @var string $pdoAdress
     */
    public static $pdoAdress = '';


    /**
     * @return PDO
     */
    public static function getSqliteConnexion()
    {
        try {
            $db = new PDO('sqlite:' . self::$pdoAdress);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PdoException $e) {
            die($e->getMessage());
        }

        return $db;
    }

    /**
     * @param string $address
     */
    public static function setPdoAddress($address)
    {
        self::$pdoAdress = $address;
    }
}
