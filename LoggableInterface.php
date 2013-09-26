<?php

namespace Havvg\Monolog\RecursiveProcessor;

interface LoggableInterface
{
    /**
     * Returns a loggable representation of the current object.
     *
     * @return mixed
     */
    public function toLogEntry();
}
