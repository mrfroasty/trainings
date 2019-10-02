<?php

namespace Training\Bundle\UserNamingBundle\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Training\Bundle\UserNamingBundle\Entity\UserNamingType;

class UserNamingController extends Controller
{
    /**
     * @Route("/", name="training_user_naming_index")
     * @Template
     * @Acl(
     *      id="training_user_naming_view",
     *      type="entity",
     *      class="TrainingUserNamingBundle:UserNamingType",
     *      permission="VIEW"
     * )
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => UserNamingType::class,
        ];
    }

    /**
     * @Route("/view/{id}", name="training_user_naming_view", requirements={"id"="\d+"})
     * @Template
     * @AclAncestor("training_user_naming_view")
     * @param UserNamingType $type
     * @return array
     */
    public function viewAction(UserNamingType $type)
    {
        return [
            'entity' => $type,
        ];
    }
}
