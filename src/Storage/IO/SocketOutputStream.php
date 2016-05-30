<?php

namespace Storage\IO;

use Storage\StorageIOException;

class SocketOutputStream extends OutputStream
{
    private $socket;

    public function __construct($address, $socketDomain, $socketType, $socketProtocol)
    {
        $this->socket = socket_create($socketDomain, $socketType, $socketProtocol);
        $status = socket_connect($this->socket, $address);
        if (!$status) {
            throw new StorageIOException('Unable connect to socket ' . $address);
        }
    }

    /**
     * @param $data Some data to send
     * @throws StorageIOException
     */
    public function write($data)
    {
        socket_write($this->socket, $data);
    }

    /**
     * @throws StorageIOException
     */
    public function close()
    {
        socket_close($this->socket);
    }
}