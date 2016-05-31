<?php

namespace Storage\Translator;

interface Translator
{

    /**
     * @throws InvalidDataException
     * @param $data
     * @return mixed
     */
    public function translate($data);
}