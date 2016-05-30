<?php

namespace Storage\IO;

use Storage\StorageIOException;

class UrlOutputStream extends OutputStream
{
    private $url;

    public function __construct($spec)
    {
        $this->url = $spec;
        // @TODO Implement connection
        throw new \RuntimeException('Out of implemenentation');
    }

    /**
     * @param $data Some data to send
     * @throws StorageIOException
     */
    public function write($data)
    {
        // TODO: Implement write() method.
        throw new \RuntimeException('Out of implemenentation');
    }

    /**
     * @throws StorageIOException
     */
    public function close()
    {
        // TODO: Implement close() method.
        throw new \RuntimeException('Out of implemenentation');
    }
}