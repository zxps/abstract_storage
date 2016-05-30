<?php

namespace Storage\Transmitter;

use Storage\Resource\Resource;

class MultiTransmitter extends Transmitter
{
    /**
     * @var
     */
    private $destinations;

    /**
     * @param Resource $source
     * @param Resource[] $destinations
     */
    public function __construct(Resource $source, array $destinations)
    {
        $this->source = $source;
        $this->destinations = $destinations;
    }

    public function transmit()
    {
        $this->atomic($this->destinations);
        foreach($this->source->getInputStream() as $data) {
            try{
                foreach($this->destinations as $destination) {
                    $this->write($destination->getOutputStream(), $data);
                }
            } catch (StorageException $e) {
                $this->atomicRollback($this->destinations);
                throw new \RuntimeException('Unable to transmit. Reason: ' . $e->getMessage());
            }
        }
        $this->atomicApply($this->destinations);
    }

}