<?php

namespace Training\Bundle\UserNamingBundle\Provider;

use Oro\Bundle\EntityBundle\Provider\EntityNameProviderInterface;
use Oro\Bundle\UserBundle\Entity\User;
use Training\Bundle\UserNamingBundle\Entity\UserNamingType;

class EntityNameProviderDecorator implements EntityNameProviderInterface
{
    /** @var EntityNameProviderInterface */
    private $originalProvider;

    /**
     * @param EntityNameProviderInterface $originalProvider
     */
    public function __construct(EntityNameProviderInterface $originalProvider)
    {
        $this->originalProvider = $originalProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getName($format, $locale, $entity)
    {
        if ($entity instanceof User) {
            /** @var $userNameType UserNamingType */
            $userNameType = $entity->getUsernamingType();
            //if not set
            if (!$entity->getUsernamingType()) {
                return $this->originalProvider->getName($format, $locale, $entity);
            }
            $format = $userNameType->getFormat();
            switch ($format) {
                case "First name only":
                    return sprintf('%s', $entity->getFirstName());
                    break;
                case "Unofficial":
                    return sprintf('%s %s', $entity->getFirstName(), $entity->getLastName());
                    break;
                case "Official":
                    return sprintf('%s %s %s %s %s', $entity->getNamePrefix(), $entity->getFirstName(), $entity->getMiddleName(), $entity->getLastName(), $entity->getNameSuffix());
                    break;
            }
        }
        return $this->originalProvider->getName($format, $locale, $entity);
    }

    /**
     * {@inheritdoc}
     */
    public function getNameDQL($format, $locale, $className, $alias)
    {
        return $this->originalProvider->getNameDQL($format, $locale, $className, $alias);
    }
}