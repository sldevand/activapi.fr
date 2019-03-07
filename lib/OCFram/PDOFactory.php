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
     * @var string
     */
    public static $lastUsedConnexion = '';
    /**
     * @var string
     */
    public static $pdoAdress = '';

    /**
     * @return PDO
     */
    public static function getMysqlConnexion()
    {

        try {
            $db = new PDO('mysql:host=localhost;dbname=listes;charset=utf8', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PdoException $e) {
            die($e->getMessage());
        }

        self::$lastUsedConnexion = 'mysql';

        return $db;
    }

    /**
     * @return PDO
     */
    public static function getSqliteConnexion()
    {
        try {
            $db = new PDO(self::$pdoAdress);
        } catch (PdoException $e) {
            die($e->getMessage());
        }

        self::$lastUsedConnexion = 'sqlite';

        return $db;
    }

    /**
     * @param $address
     */
    public static function setPdoAddress($address)
    {
        self::$pdoAdress = $address;
    }
}
