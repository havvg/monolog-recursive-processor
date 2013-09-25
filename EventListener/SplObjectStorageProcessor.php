<?php

namespace Havvg\Monolog\RecursiveProcessor\EventListener;

use Havvg\Monolog\RecursiveProcessor\LogEvents;
use Havvg\Monolog\RecursiveProcessor\Event\ProcessDataEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SplObjectStorageProcessor implements EventSubscriberInterface
{
    public function process(ProcessDataEvent $event)
    {
        $data = $event->getData();
        if (!$data instanceof \SplObjectStorage) {
            return;
        }

        $storage = array();
        foreach ($data as $eachObject) {
            $storage[$data->key()] = array(
                'object' => $eachObject,
                'info' => $data->getInfo(),
            );
        }

        $event->setData($storage);

        // The propagation is not stopped, as the default implementation will process the new structure (array).
    }

    public static function getSubscribedEvents()
    {
        return array(
            LogEvents::PROCESS_STRUCTURE => array('process', -96),
        );
    }
}
