<?php

namespace Havvg\Monolog\RecursiveProcessor\Tests\Fixtures;

use Havvg\Monolog\RecursiveProcessor\LoggableInterface;

class Loggable implements LoggableInterface
{
    public $data = array();

    public function toLogEntry()
    {
        return $this->data;
    }
}
