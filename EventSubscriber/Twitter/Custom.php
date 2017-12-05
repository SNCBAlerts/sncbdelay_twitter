<?php

namespace drupol\sncbdelay_twitter\EventSubscriber\Twitter;

use drupol\sncbdelay_twitter\EventSubscriber\AbstractTwitterCustomEventSubscriber;
use Symfony\Component\EventDispatcher\Event;

class Custom extends AbstractTwitterCustomEventSubscriber
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
        $message = $event->getStorage()['message'];
        $twig = $this->getContainer()->get('twig');

        return $twig->render(
            '@SNCBDelayTwitter/custom.twig',
            [
                'message' => $message,
            ]
        );
    }
}
