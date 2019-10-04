<?php

namespace Training\Bundle\UserNamingBundle\EventListener;

use Doctrine\Common\Persistence\ManagerRegistry;

use Oro\Bundle\UIBundle\Event\BeforeListRenderEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserViewNamingListener
{
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker
    ){
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param BeforeListRenderEvent $event
     */
    public function onUserView(BeforeListRenderEvent $event)
    {
        if (!$this->authorizationChecker->isGranted('training_user_naming_info')) {
            return;
        }

        $user = $event->getEntity();
        if (!$user) {
            return;
        }

        $template = $event->getEnvironment()->render(
            'TrainingUserNamingBundle:User:namingData.html.twig',
            ['entity' => $user]
        );
        $subBlockId = $event->getScrollData()->addSubBlock(0);
        $event->getScrollData()->addSubBlockData(0, $subBlockId, $template);
    }
}