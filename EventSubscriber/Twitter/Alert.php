<?php

namespace drupol\sncbdelay_twitter\EventSubscriber\Twitter;

use drupol\sncbdelay_twitter\EventSubscriber\AbstractTwitterAlertEventSubscriber;
use Symfony\Component\EventDispatcher\Event;

class Alert extends AbstractTwitterAlertEventSubscriber
{
    /**
     * @param \Symfony\Component\EventDispatcher\Event $event
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return mixed
     */
    public function getMessage(Event $event)
    {
        $disturbance = $event->getStorage()['disturbance'];
        date_default_timezone_set('Europe/Brussels');

        return $this->twig->render(
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
