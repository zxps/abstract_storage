<?php

namespace Services;

use Storage\ACID\AtomicOperation;
use Storage\Connection\Protocol;
use Storage\Permissions;
use Storage\Resource\Resource;
use Storage\Storage;
use Storage\StorageResource;

class AwesomeCloudStorage implements Storage, AtomicOperation
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
            Protocol::TYPE_URL,
            $permissions,
            'http://some-remote-service.com/api/write');
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
        return 'Some remote cloud storage';
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
        // @TODO Call before all operations
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