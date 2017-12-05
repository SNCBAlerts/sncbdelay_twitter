<?php

namespace drupol\sncbdelay_twitter\EventSubscriber\Twitter;

use drupol\sncbdelay_twitter\EventSubscriber\AbstractTwitterCanceledEventSubscriber;
use Symfony\Component\EventDispatcher\Event;

class Canceled extends AbstractTwitterCanceledEventSubscriber
{
    /**
     * @param \Symfony\Component\EventDispatcher\Event $event
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return mixed
     */
    public function getMessage(Event $event)
    {
        $departure = $event->getStorage()['departure'];
        $station = $event->getStorage()['station'];
        $lines = $event->getStorage()['lines'];

        date_default_timezone_set('Europe/Brussels');

        $twig = $this->getContainer()->get('twig');

        return $twig->render(
            '@SNCBDelayTwitter/canceled.twig',
            [
                'train' => $departure['vehicle'],
                'station_from' => $station['name'],
                'station_to' => $departure['stationinfo']['name'],
                'delay' => $departure['delay']/60,
                'time' => date('H:i', $departure['time']),
                'url' => $departure['departureConnection'],
                'lines' => $lines,
            ]
        );
    }
}
