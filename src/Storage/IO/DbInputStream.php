<?php

namespace Storage\IO;

use Storage\StorageIOException;

class DbInputStream extends InputStream
{

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @var \PDOStatement
     */
    private $cursor;

    /**
     * @var
     */
    private $query;

    /**
     * @var mixed
     */
    private $buffer;

    /**
     * @var int
     */
    private $readCount = 0;

    /**
     * @param $dsn
     * @param $user
     * @param $password
     * @param array $options
     */
    public function __construct($dsn, $user, $password, $query, array $options = [])
    {
        $this->query = $options['query'];
        unset($options['query']);
        $this->connection = new \PDO($dsn, $user, $password, $options);
    }

    /**
     * @throws StorageIOException
     */
    public function read($length)
    {
        if ($this->cursor === null) {
            $this->cursor = $this->connection->prepare($this->query);
        }
        $this->buffer = $this->cursor->fetch();
        $this->readCount++;
        return $this->buffer;
    }

    /**
     * @throws StorageIOException
     */
    public function reset()
    {
        if (null !== $this->cursor) {
            $this->reset();
            $this->readCount = 0;
        }
    }

    /**
     * @throws StorageIOException
     */
    public function close()
    {
        $this->reset();
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
        return $this->buffer;
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
        $this->read(null);
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
        return $this->readCount;
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
        return null !== $this->buffer;
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
        $this->reset();
    }
}