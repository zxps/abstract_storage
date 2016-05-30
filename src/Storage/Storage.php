<?php

namespace Storage;

use Storage\Connection\Protocol;

interface Storage
{
    /**
     * @return StorageResource
     */
    public function getResource();

    /**
     * @return void
     * @throws
     */
    public function close();

    /**
     * Returns storage name
     * @return string
     */
    public function getName();

    /**
     * @return Protocol
     */
    public function getProtocol();
}