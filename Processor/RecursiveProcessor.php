<?php

namespace Havvg\Monolog\RecursiveProcessor\Processor;

use Havvg\Monolog\RecursiveProcessor\Event\ProcessDataEvent;
use Havvg\Monolog\RecursiveProcessor\LogEvents;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecursiveProcessor implements EventSubscriberInterface
{
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(array $record)
    {
        if (empty($record['context'])) {
            return $record;
        }

        $record['context'] = $this->process($record['context'], $record);

        return $record;
    }

    protected function process($data, $record)
    {
        if (!$data instanceof \Traversable and !is_array($data)) {
            $event = new ProcessDataEvent($data, $record);
            $this->dispatcher->dispatch(LogEvents::PROCESS_ENTRY, $event);

            return $event->getData();
        }

        $event = new ProcessDataEvent($data, $record);
        $this->dispatcher->dispatch(LogEvents::PROCESS_STRUCTURE, $event);

        return $event->getData();
    }

    public function processStructure(ProcessDataEvent $event)
    {
        $data = $event->getData();
        $record = $event->getRecord();

        foreach ($data as $key => $entry) {
            $data[$key] = $this->process($entry, $record);
        }

        $event->setData($data);
    }

    public static function getSubscribedEvents()
    {
        return array(
            LogEvents::PROCESS_STRUCTURE => array('processStructure', -128),
        );
    }
}
