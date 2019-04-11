<?php

namespace drupol\sncbdelay_twitter\EventSubscriber\Twitter;

use drupol\sncbdelay_twitter\EventSubscriber\AbstractTwitterDelayEventSubscriber;
use Symfony\Component\EventDispatcher\Event;

class Delay extends AbstractTwitterDelayEventSubscriber
{

    /**
     * @param \Symfony\Component\EventDispatcher\Event $event
     *
     * @return mixed
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getMessage(Event $event)
    {
        $departure = $event->getStorage()['departure'];
        $station = $event->getStorage()['station'];
        $lines = $event->getStorage()['lines'];

        date_default_timezone_set('Europe/Brussels');

        return $this->twig->render(
            '@SNCBDelayTwitter/delay.twig',
            [
                'train' => $departure['vehicle'],
                'station_from' => $station['name'],
                'station_to' => $departure['stationinfo']['name'],
                'delay' => $departure['delay']/60,
                'date' => date('H:i', $departure['time']),
                'url' => $departure['departureConnection'],
                'platform' => $departure['platform'],
                'lines' => $lines,
            ]
        );
    }
}
