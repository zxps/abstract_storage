<?php

namespace Services;

use Storage\ACID\AtomicOperation;
use Storage\Connection\Protocol;
use Storage\NoPermissionsException;
use Storage\Permissions;
use Storage\Resource\Resource;
use Storage\Storage;
use Storage\StorageResource;

class RemoteSocketStorage implements Storage, AtomicOperation
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
        $permissions = new Permissions(Permissions::WRITE);
        $this->protocol = new Protocol(
            Protocol::TYPE_SOCKET,
            $permissions,
            'http://some-remote-host.com:44550/api/write');
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