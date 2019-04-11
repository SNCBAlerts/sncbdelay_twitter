<?php

namespace drupol\sncbdelay_twitter\EventSubscriber\Twitter;

use drupol\sncbdelay_twitter\EventSubscriber\AbstractTwitterCustomEventSubscriber;
use Symfony\Component\EventDispatcher\Event;

class Custom extends AbstractTwitterCustomEventSubscriber
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
        return $this->twig->render(
            '@SNCBDelayTwitter/custom.twig',
            [
                'message' => $event->getStorage()['message'],
            ]
        );
    }
}
