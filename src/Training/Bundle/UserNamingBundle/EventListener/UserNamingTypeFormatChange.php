<?php

namespace Training\Bundle\UserNamingBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Oro\Bundle\SearchBundle\Async\Topics;
use Oro\Component\MessageQueue\Client\MessageProducerInterface;
use Training\Bundle\UserNamingBundle\Entity\UserNamingType;

class UserNamingTypeFormatChange
{
    /**
     * @var MessageProducerInterface
     */
    private $producer;

    /**
     * @param MessageProducerInterface $producer
     */
    public function __construct(MessageProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @param UserNamingType $userNamingType
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(UserNamingType $userNamingType, PreUpdateEventArgs $args)
    {
        if (!$args->hasChangedField('format')) {
            return;
        }

        //
        $this->producer->send(\Training\Bundle\UserNamingBundle\Provider\Topics::INDEX_ENTITY, [
            'entityClass' => UserNamingType::class,
        ]);
    }
}