<?php

namespace Storage\Transmitter;

use Storage\ACID\AtomicConsistencyException;
use Storage\ACID\AtomicOperation;
use Storage\IO\InputStream;
use Storage\IO\OutputStream;
use Storage\Resource\Resource;
use Storage\Storage;
use Storage\StorageException;

class Transmitter
{

    /**
     * @var Resource
     */
    protected $source;

    /**
     * @var Resource
     */
    private $destination;

    /**
     * @param Resource $source
     * @param Resource $destination
     */
    public function __construct(Resource $source, Resource $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    public function transmit()
    {
        $this->atomic([$this->destination]);
        foreach($this->source->getInputStream() as $data) {
            try{
                $this->write($this->destination->getOutputStream(), $data);
            } catch (StorageException $e) {
                $this->atomicRollback([$this->destination]);
                throw new \RuntimeException('Unable to transmit. Reason: ' . $e->getMessage());
            }
        }
        $this->atomicApply([$this->destination]);
    }

    protected function checkAtomic(array $destinations)
    {
        $count = 0;
        foreach($destinations as $dest) {
            if ($dest instanceof AtomicOperation) {
                $count++;
            }
        }
        if ($count > 0 && count($destinations) != $count) {
            throw new AtomicConsistencyException();
        }
    }

    protected function atomic(array $destinations)
    {
        $this->checkAtomic($destinations);
        foreach($destinations as $dest) {
            if ($dest instanceof AtomicOperation) {
                $dest->begin();
            }
        }
    }

    protected function atomicRollback(array $destinations)
    {
        foreach($destinations as $dest) {
            if ($dest instanceof AtomicOperation) {
                $dest->rollback();
            }
        }
    }

    protected function atomicApply(array $destinations)
    {
        foreach($destinations as $dest) {
            if ($dest instanceof AtomicOperation) {
                $dest->commit();
            }
        }
    }

    protected function write(OutputStream $stream, $data)
    {
        $stream->write($data);
    }
}