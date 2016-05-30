<?php

namespace Storage\IO;

use Storage\StorageIOException;

abstract class InputStream implements \Iterator
{
    /**
     * @throws StorageIOException
     */
    abstract public function read($length);

    /**
     * @throws StorageIOException
     */
    abstract public function reset();

    /**
     * @throws StorageIOException
     */
    abstract public function close();
}