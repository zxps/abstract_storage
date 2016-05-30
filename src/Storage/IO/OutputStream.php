<?php

namespace Storage\IO;

use Storage\StorageIOException;

abstract class OutputStream
{
    /**
     * @param $data Some data to send
     * @throws StorageIOException
     */
    abstract public function write($data);

    /**
     * @throws StorageIOException
     */
    abstract public function close();

}