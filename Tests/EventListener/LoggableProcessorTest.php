<?php

namespace Havvg\Monolog\RecursiveProcessor\Tests\EventListener;

use Havvg\Monolog\RecursiveProcessor\Event\ProcessDataEvent;
use Havvg\Monolog\RecursiveProcessor\EventListener\LoggableProcessor;

use Havvg\Monolog\RecursiveProcessor\Tests\Fixtures\Loggable;

/**
 * @covers Havvg\Monolog\RecursiveProcessor\EventListener\LoggableProcessor
 */
class LoggableProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessor()
    {
        $expected = array(
            'foo' => 'bar',
        );

        $data = new Loggable();
        $data->data = $expected;

        $event = new ProcessDataEvent($data, array());
        $processor = new LoggableProcessor();
        $processor->process($event);

        $processed = $event->getData();

        $this->assertEquals($expected, $processed);
    }

    public function testProcessorNoLoggable()
    {
        $event = new ProcessDataEvent(array(), array());
        $processor = new LoggableProcessor();
        $processor->process($event);

        $this->assertEquals(array(), $event->getData());
    }
}
