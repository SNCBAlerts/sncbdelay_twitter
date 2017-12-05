<?php

namespace drupol\sncbdelay_twitter\EventSubscriber\Twitter;

use drupol\sncbdelay_twitter\EventSubscriber\AbstractTwitterAlertEventSubscriber;
use Symfony\Component\EventDispatcher\Event;

class Alert extends AbstractTwitterAlertEventSubscriber
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
        $disturbance = $event->getStorage()['disturbance'];
        date_default_timezone_set('Europe/Brussels');

        $twig = $this->getContainer()->get('twig');

        return $twig->render(
            '@SNCBDelayTwitter/alert.twig',
            [
                'title' => strip_tags($disturbance['title']),
                'description' => strip_tags($disturbance['description']),
                'url' => $disturbance['link'],
                'time' => date('H:i', $disturbance['timestamp']),
            ]
        );
    }
}
