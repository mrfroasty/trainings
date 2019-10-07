<?php

namespace Training\Bundle\UserNamingBundle\Async;

use Doctrine\ORM\EntityManager;
use Oro\Component\MessageQueue\Client\TopicSubscriberInterface;
use Oro\Component\MessageQueue\Consumption\MessageProcessorInterface;
use Oro\Component\MessageQueue\Transport\MessageInterface;
use Oro\Component\MessageQueue\Transport\SessionInterface;
use Psr\Log\LoggerInterface;
use Training\Bundle\UserNamingBundle\Entity\UserNamingType;
use Training\Bundle\UserNamingBundle\Provider\Topics;

class SyncUserNamingTypeExample implements MessageProcessorInterface, TopicSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManager $entityManager,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param MessageInterface $message
     * @param SessionInterface $session
     *
     * @return string
     */
    public function process(MessageInterface $message, SessionInterface $session)
    {
        $example = "Mr. Example Jr";
        $this->entityManager->getUnitOfWork()
            ->computeChangeSet(
                $this->entityManager->getClassMetadata(UserNamingType::class),
                $example
            );
    }

    /**
     * * ['topicName']
     * * ['topicName' => ['processorName' => 'processor', 'destinationName' => 'destination']]
     * processorName, destinationName - optional.
     *
     * @return array
     */
    public static function getSubscribedTopics()
    {
        return [Topics::INDEX_ENTITY];
    }
}