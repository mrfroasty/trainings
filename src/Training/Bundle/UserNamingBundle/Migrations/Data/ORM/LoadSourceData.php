<?php

namespace Training\Bundle\UserNamingBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Training\Bundle\UserNamingBundle\Entity\UserNamingType;

class LoadSourceData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = [
        'Official' => 'PREFIX FIRST MIDDLE LAST SUFFIX',
        'Unofficial' => 'FIRST LAST',
        'First name only' => 'FIRST'
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $format => $title) {
            $userNameType = new UserNamingType();
            $userNameType->setFormat($format)
                ->setTitle($title);
            $manager->persist($userNameType);
        }

        $manager->flush();
    }
}