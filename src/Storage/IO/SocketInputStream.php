<?php

namespace Storage\IO;

use Storage\StorageIOException;

class SocketInputStream extends InputStream
{

    /**
     * @var resource
     */
    private $socket;

    /**
     * @param $address
     * @param $socketDomain
     * @param $socketType
     * @param $socketProtocol
     * @throws StorageIOException
     */
    public function __construct($address, $socketDomain, $socketType, $socketProtocol)
    {
        $this->socket = socket_create($socketDomain, $socketType, $socketProtocol);
        $status = socket_connect($this->socket, $address);
        if (!$status) {
            throw new StorageIOException('Unable connect to socket ' . $address);
        }
    }

    /**
     * @throws StorageIOException
     */
    public function read($length)
    {
        return socket_read($this->socket, $length);
    }

    /**
     * @throws StorageIOException
     */
    public function reset()
    {
        // TODO: Implement reset() method.
    }

    /**
     * @throws StorageIOException
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
}}