<?php

namespace Storage\ACID;

interface AtomicOperation
{
    public function begin();

    public function commit();

    public function rollback();
}