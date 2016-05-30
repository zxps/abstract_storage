<?php

namespace Services;

use Storage\ACID\AtomicOperation;
use Storage\Connection\Protocol;
use Storage\NoPermissionsException;
use Storage\Permissions;
use Storage\Resource\Resource;
use Storage\Storage;
use Storage\StorageResource;

class MyLocalDatabaseStorage implements Storage, AtomicOperation
{

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var Protocol
     */
    private $protocol;


    public function __construct()
    {
        $permissions = new Permissions(Permissions::READ);
        $this->protocol = new Protocol(
            Protocol::TYPE_DB,
            $permissions,
            [
                'dsn' => '',
                'user' => 'dbUser',
                'password' => 'dbPassword',
                'table' => 'table_name',
                'query' => 'Select * from table_name Join another_table Where some_field > 150'
            ]);
        $this->resource = new Resource($this);
    }

    /**
     * @return StorageResource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return void
     * @throws
     */
    public function close()
    {
        try{
            $this->resource->getInputStream()->close();
        } catch (NoPermissionsException $e) {

        }
        try {
            $this->resource->getOutputStream()->close();
        } catch (NoPermissionsException $e) {

        }
    }

    /**
     * Returns storage name
     *
     * @return string
     */
    public function getName()
    {
        return 'My Local Database Storage';
    }

    /**
     * @return Protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @implements AtomicOperation::begin
     */
    public function begin()
    {
        // @TODO Calls before all operations
    }

    /**
     * @implements AtomicOperation::commit
     */
    public function commit()
    {
        // @TODO: Apply all
    }

    /**
     * @implements AtomicOperation::rollback
     */
    public function rollback()
    {
        // @TODO: Rollback if something went wrong
    }
}