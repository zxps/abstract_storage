<?php

namespace Storage\IO;

use Storage\StorageIOException;

class FileOutputStream extends OutputStream
{
    private $file;

    public function __construct($filename)
    {
        if (!file_exists($filename)) {
            throw new StorageIOException('File ' . $filename . ' not exists');
        }
        $this->file = fopen($filename, 'a');
    }

    public function write($data)
    {
        fwrite($this->file, $data);
    }

    public function close()
    {
        fclose($this->file);
    }
}