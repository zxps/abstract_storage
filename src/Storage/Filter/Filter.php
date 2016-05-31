<?php

namespace Storage\Filter;

use Storage\Resource\Resource;

interface Filter
{

    /**
     *
     * @param $data
     * @return mixed
     */
    public function handle(Resource $source, Resource $destination, $data);

    /**
     * If true - will not send data to an output resource
     * @param $data
     * @return bool
     */
    public function skip(Resource $source, Resource $destination, $data);

    /**
     * For filters sorting
     * @return int
     */
    public function getPriority();
} 