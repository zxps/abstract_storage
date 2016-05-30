<?php

namespace Services;

use Storage\ACID\AtomicOperation;
use Storage\Connection\Protocol;
use Storage\Permissions;
use Storage\Resource\Resource;
use Storage\Storage;
use Storage\StorageResource;

class AnyDatabaseStorage implements Storage, AtomicOperation
{

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var Protocol
     */
    private $protocol;


    public function __construct(Permissions $permissions)
    {
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
        if ($this->protocol->getPermissions()->is(Permissions::READ)) {
            $this->resource->getInputStream()->close();
        }
        if ($this->protocol->getPermissions()->is(Permissions::WRITE)) {
            $this->resource->getOutputStream()->close();
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