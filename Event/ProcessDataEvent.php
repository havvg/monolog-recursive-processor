<?php

namespace Havvg\Monolog\RecursiveProcessor\Event;

use Symfony\Component\EventDispatcher\Event;

class ProcessDataEvent extends Event
{
    protected $data;
    protected $record;

    public function __construct($data, array $record)
    {
        $this->data = $data;
        $this->record = $record;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getRecord()
    {
        return $this->record;
    }
}
