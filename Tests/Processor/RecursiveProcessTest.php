<?php

namespace Havvg\Monolog\RecursiveProcessor\Tests\Processor;

use Havvg\Monolog\RecursiveProcessor\LogEvents;
use Havvg\Monolog\RecursiveProcessor\Event\ProcessDataEvent;
use Havvg\Monolog\RecursiveProcessor\Processor\RecursiveProcessor;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @covers Havvg\Monolog\RecursiveProcessor\Processor\RecursiveProcessor
 */
class RecursiveProcessTest extends \PHPUnit_Framework_TestCase
{
    public function testNoContext()
    {
        $record = array(
            'extra' => array('foo' => 'bar'),
        );

        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher
            ->expects($this->never())
            ->method('dispatch')
        ;

        $processor = new RecursiveProcessor($dispatcher);
        $processed = $processor->__invoke($record);

        $this->assertEquals($record, $processed);
    }

    public function testFlatData()
    {
        $data = new \stdClass();
        $data->foo = 'bar';

        $record = array(
            'context' => $data,
        );

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(LogEvents::PROCESS_ENTRY, function(ProcessDataEvent $event) {
            $event->setData(array('foo' => $event->getData()->foo));
        });

        $processor = new RecursiveProcessor($dispatcher);
        $dispatcher->addSubscriber($processor);

        $processed = $processor->__invoke($record);

        $expected = array(
            'context' => array('foo' => 'bar'),
        );
        $this->assertEquals($expected, $processed);
    }

    public function testRecursiveData()
    {
        $data = new \stdClass();
        $data->foo = 'bar';

        $record = array(
            'context' => array(
                'foo' => $data,
                'bar' => $data,
                'flat' => 5,
            ),
        );

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(LogEvents::PROCESS_ENTRY, function(ProcessDataEvent $event) {
            if (!$event->getData() instanceof \stdClass) {
                return;
            }

            $event->setData(array('foo' => $event->getData()->foo));
        });

        $processor = new RecursiveProcessor($dispatcher);
        $dispatcher->addSubscriber($processor);

        $processed = $processor->__invoke($record);

        $expected = array(
            'context' => array(
                'foo'=> array('foo' => 'bar'),
                'bar' => array('foo' => 'bar'),
                'flat' => 5,
            ),
        );
        $this->assertEquals($expected, $processed);
    }
}
