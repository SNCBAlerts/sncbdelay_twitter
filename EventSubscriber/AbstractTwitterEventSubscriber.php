<?php

namespace drupol\sncbdelay_twitter\EventSubscriber;

use Doctrine\ORM\EntityManager;
use drupol\sncbdelay\EventSubscriber\AbstractEventSubscriber;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Event;

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
     * @param \Psr\Container\ContainerInterface $container
     * @param \Twig_Environment $twig
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Doctrine\ORM\EntityManager $doctrine
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container, \Twig_Environment $twig, LoggerInterface $logger, CacheItemPoolInterface $cache, EntityManager $doctrine)
    {
        $this->twitter = $container->get('sncbdelay_twitter.twitter');
        $this->brevity = $container->get('sncbdelay_twitter.brevity');
        parent::__construct($container, $twig, $logger, $cache, $doctrine);
    }

    /**
     * @param \Symfony\Component\EventDispatcher\Event $event
     */
    public function process(Event $event)
    {
        $station = $event->getStorage()['station'];

        $lat = floatval($station['locationY']);
        $long = floatval($station['locationX']);

        $twitter = [
            'status' => $this->shortenMessage($this->getMessage($event)),
            'lat' => $lat,
            'long' => $long,
            'display_coordinates' => 'true',
        ];

        $this->twitter->post(
            'statuses/update',
            $twitter
        );

        if (200 == $this->twitter->getLastHttpCode()) {
            $this->logger->notice('Posted on Twitter.', ['twitter' => $twitter]);
        } else {
            $this->logger->notice('Twitter post failed', ['twitter' => $twitter, 'code' => $this->twitter->getLastHttpCode(), 'body' => $this->twitter->getLastBody()]);
        }
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function shortenMessage($text)
    {
        return $this->brevity->shorten($text);
    }
}
