<?php

namespace Havvg\Monolog\RecursiveProcessor\EventListener;

use Havvg\Monolog\RecursiveProcessor\LogEvents;
use Havvg\Monolog\RecursiveProcessor\LoggableInterface;
use Havvg\Monolog\RecursiveProcessor\Event\ProcessDataEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoggableProcessor implements EventSubscriberInterface
{
    public function process(ProcessDataEvent $event)
    {
        $data = $event->getData();
        if (!$data instanceof LoggableInterface) {
            return;
        }

        $event->setData($data->toLogEntry());
    }

    public static function getSubscribedEvents()
    {
        return [
            LogEvents::PROCESS_ENTRY => [ 'process', -72 ],
            LogEvents::PROCESS_STRUCTURE => [ 'process', -72 ],
        ];
    }
}
