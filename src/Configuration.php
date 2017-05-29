<?php

namespace Karriere\Mocky;

use ArrayObject;

class Configuration extends ArrayObject
{
    /**
     * Configuration constructor.
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        parent::__construct($this->load($configFile));
    }

    private function load($file)
    {
        return require $file;
    }
}
