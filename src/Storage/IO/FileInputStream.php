<?php

namespace Storage\IO;

use Storage\StorageIOException;

class FileInputStream extends InputStream
{
    private $file;

    private $readBytes;

    private $buffer = null;

    private $readCount = 0;

    const DEFAULT_READ_BYTES = 1024;

    public function __construct($filename, $readBytes = self::DEFAULT_READ_BYTES)
    {
        if (!file_exists($filename)) {
            throw new StorageIOException('File ' . $filename . ' not exists');
        }
        $this->readBytes = $readBytes;
        $this->file = fopen($filename, 'r');
    }

    public function read($length)
    {
        $this->buffer = fread($this->file, $length);
        $this->readCount++;
        return $this->buffer;
    }

    public function close()
    {
        fclose($this->file);
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
        $this->read($this->readBytes);
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

    /**
     * @throws StorageIOException
     */
    public function reset()
    {
        fseek($this->file, 0);
        $this->readCount = 0;
    }
}