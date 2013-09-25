<?php

namespace Havvg\Monolog\RecursiveProcessor\Tests\EventListener;

use Havvg\Monolog\RecursiveProcessor\Event\ProcessDataEvent;
use Havvg\Monolog\RecursiveProcessor\EventListener\SplObjectStorageProcessor;

/**
 * @covers Havvg\Monolog\RecursiveProcessor\EventListener\SplObjectStorageProcessor
 */
class SplObjectStorageProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessor()
    {
        $data = new \SplObjectStorage();
        $obj = new \stdClass();
        $data->attach($obj, 'foo');

        $event = new ProcessDataEvent($data, array());
        $processor = new SplObjectStorageProcessor();
        $processor->process($event);

        $processed = $event->getData();
        $expected = array(
            0 => array(
                'object' => $obj,
                'info' => 'foo',
            ),
        );

        $this->assertEquals($expected, $processed);
    }

    public function testProcessorNoStorage()
    {
        $event = new ProcessDataEvent(array(), array());
        $processor = new SplObjectStorageProcessor();
        $processor->process($event);

        $this->assertEquals(array(), $event->getData());
    }
}
