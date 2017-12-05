<?php

namespace drupol\sncbdelay_twitter\EventSubscriber;

use Symfony\Component\EventDispatcher\Event;

abstract class AbstractTwitterAlertEventSubscriber extends AbstractTwitterEventSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return ['sncbdelay.message.alert' => 'handler'];
    }

    /**
     * @param \Symfony\Component\EventDispatcher\Event $event
     */
    public function process(Event $event)
    {
        $twitter = [
            'status' => $this->shortenMessage($this->getMessage($event)),
        ];

        $this->twitter->post(
            'statuses/update',
            $twitter
        );

        if (200 == $this->twitter->getLastHttpCode()) {
            $this->logger->notice('Posted on Twitter.', ['twitter' => $twitter]);
        } else {
            $this->logger->notice('Twitter post failed', ['twitter' => $twitter, 'body' => $this->twitter->getLastBody()]);
        }
    }
}
