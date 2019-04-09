<?php

namespace drupol\sncbdelay_twitter\EventSubscriber\Twitter;

use drupol\sncbdelay_twitter\EventSubscriber\AbstractTwitterCustomEventSubscriber;
use Symfony\Component\EventDispatcher\Event;

class Custom extends AbstractTwitterCustomEventSubscriber
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
        return $this->twig->render(
            '@SNCBDelayTwitter/custom.twig',
            [
                'message' => $event->getStorage()['message'],
            ]
        );
    }
}
