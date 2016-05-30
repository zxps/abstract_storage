<?php

namespace Storage\IO;

use Storage\StorageIOException;

class DbOutputStream extends OutputStream
{

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @param $dsn
     * @param $user
     * @param $password
     * @param array $options
     */
    public function __construct($dsn, $user, $password, $table, array $options = [])
    {
        $this->table = $table;
        $this->connection = new \PDO($dsn, $user, $password, $options);
    }

    /**
     * @param $data Some data to send
     * @throws StorageIOException
     */
    public function write($data)
    {
        $keys = [];
        $values = [];

        // @TODO Combine $data to $keys and $values

        $this->connection->prepare('INSERT INTO ' . $this->table . ' ('.implode(',', $keys).') VALUES('.implode(',',$values).')');
    }

    /**
     * @throws StorageIOException
     */
    public function close()
    {
        // TODO: Implement close() method.
    }
}