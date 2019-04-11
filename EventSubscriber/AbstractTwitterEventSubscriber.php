<?php

namespace drupol\sncbdelay_twitter\EventSubscriber;

use Abraham\TwitterOAuth\TwitterOAuth;
use Doctrine\ORM\EntityManagerInterface;
use drupol\sncbdelay\EventSubscriber\AbstractEventSubscriber;
use Kylewm\Brevity\Brevity;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\Event;
use Twig\Environment;

abstract class AbstractTwitterEventSubscriber extends AbstractEventSubscriber
{
    /**
     * @var \Abraham\TwitterOAuth\TwitterOAuth
     */
    protected $twitter;

    /**
     * @var \Kylewm\Brevity\Brevity
     */
    protected $brevity;

    /**
     * AbstractTwitterDelayEventSubscriber constructor.
     *
     * @param \Abraham\TwitterOAuth\TwitterOAuth $twitter
     * @param \Kylewm\Brevity\Brevity $brevity
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface $parameters
     * @param \Twig\Environment $twig
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Psr\Cache\CacheItemPoolInterface $cache
     * @param \Doctrine\ORM\EntityManagerInterface $doctrine
     */
    public function __construct(TwitterOAuth $twitter, Brevity $brevity, ContainerBagInterface $parameters, Environment $twig, LoggerInterface $logger, CacheItemPoolInterface $cache, EntityManagerInterface $doctrine)
    {
        $this->twitter = $twitter;
        $this->brevity = $brevity;

        parent::__construct($parameters, $twig, $logger, $cache, $doctrine);
    }

    /**
     * @param \Symfony\Component\EventDispatcher\Event $event
     */
    public function process(Event $event)
    {
        $station = $event->getStorage()['station'];

        $twitter = [
            'status' => $this->shortenMessage($this->getMessage($event)),
            'lat' => (float) $station['locationY'],
            'long' => (float) $station['locationX'],
            'display_coordinates' => 'true',
        ];

        $this->twitter->post(
            'statuses/update',
            $twitter
        );

        if (200 === $this->twitter->getLastHttpCode()) {
            $this->logger->notice('Posted on Twitter.', ['twitter' => $twitter]);
        } else {
            $this->logger->notice('Twitter post failed', ['twitter' => $twitter, 'code' => $this->twitter->getLastHttpCode(), 'body' => $this->twitter->getLastBody()]);
        }
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function shortenMessage(string $text)
    {
        return $this->brevity->shorten($text);
    }
}
