<?php

namespace Storage\Translator;

/**
 * Class DefaultDataTranslator
 * Do nothing
 *
 * @package Storage\Translator
 */
class DefaultTranslator implements Translator
{

    /**
     * @throws InvalidDataException
     * @param $data
     * @return mixed
     */
    public function translate($data)
    {
        return $data;
    }
}