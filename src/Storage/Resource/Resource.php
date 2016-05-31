<?php

namespace Storage\Resource;

use Storage\Connection\Protocol;
use Storage\IO\FileInputStream;
use Storage\IO\FileOutputStream;
use Storage\IO\InputStream;
use Storage\IO\OutputStream;
use Storage\IO\SocketInputStream;
use Storage\IO\SocketOutputStream;
use Storage\IO\UrlInputStream;
use Storage\IO\UrlOutputStream;
use Storage\NoPermissionsException;
use Storage\Permissions;
use Storage\Storage;

class Resource
{

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var OutputStream
     */
    private $output = null;

    /**
     * @var InputStream
     */
    private $input = null;

    /**
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return OutputStream
     * @throws NoPermissionsException
     */
    public function getOutputStream()
    {
        if (!$this->storage->getProtocol()->getPermissions()->is(Permissions::WRITE)) {
            throw new NoPermissionsException('No write permissions');
        }
        if (null !== $this->output) {
            return $this->output;
        }

        $protocol = $this->storage->getProtocol();

        if ($protocol->is(Protocol::TYPE_FILE)) {
            $this->output = new FileOutputStream($protocol->getSpec());
        } else if ($protocol->is(Protocol::TYPE_SOCKET)) {
            $this->output =
                new SocketOutputStream(
                    $protocol->getSpec(),
                    $protocol->getOption('socket.domain', AF_INET),
                    $protocol->getOption('socket.type', SOCK_RAW),
                    $protocol->getOption('socket.protocol', 1)
                );
        } else if ($protocol->is(Protocol::TYPE_URL)) {
            $this->output = new UrlOutputStream($protocol->getSpec());
        }
        return $this->output;
    }

    /**
     * @return InputStream
     * @throws NoPermissionsException
     */
    public function getInputStream()
    {
        if (!$this->storage->getProtocol()->getPermissions()->is(Permissions::READ)) {
            throw new NoPermissionsException('No read permissions');
        }
        if (null !== $this->input) {
            return $this->input;
        }
        $protocol = $this->storage->getProtocol();

        if ($protocol->is(Protocol::TYPE_FILE)) {
            $this->input = new FileInputStream($protocol->getSpec());
        } else if ($protocol->is(Protocol::TYPE_SOCKET)) {
            $this->input =
                new SocketInputStream(
                    $protocol->getSpec(),
                    $protocol->getOption('socket.domain', AF_INET),
                    $protocol->getOption('socket.type', SOCK_RAW),
                    $protocol->getOption('socket.protocol', 1)
                );
        } else if ($protocol->is(Protocol::TYPE_URL)) {
            $this->input = new UrlInputStream($protocol->getSpec());
        }
        return $this->input;
    }

    public function __destruct()
    {
        $this->storage->close();
    }
}