<?php

namespace Tests\Api;

/**
 * Interface ManagerPDOInterfaceTest
 * @package Tests\Api
 */
interface ManagerPDOInterfaceTest
{
    public static function dropAndCreateTables();

    public function getManager();

    public function testSave($entity, $expected);

    public function testGetAll($entities, $expected);

    public function testDelete($entity, $expected);

    public function saveProvider();

    public function getAllProvider();

    public function deleteProvider();
}
