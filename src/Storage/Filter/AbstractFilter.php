<?php

namespace Storage\Filter;

abstract class AbstractFilter implements Filter
{
    private $priority;

    public final function __construct($priority)
    {
        $this->priority = (int) $priority;
    }

    public function getPriority()
    {
        return $this->priority;
    }
}