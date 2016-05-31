<?php

namespace Storage\Transmitter;

use Storage\NoPermissionsException;
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

    /**
     * @override
     * @throws NoPermissionsException
     */
    public function start()
    {
        foreach($this->destinations as $destination) {
            $this->execute(
                $this->source,
                $destination,
                $this->source->getInputStream(),
                $destination->getOutputStream()
            );
        }

    }

}