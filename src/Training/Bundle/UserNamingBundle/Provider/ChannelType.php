<?php

namespace Training\Bundle\UserNamingBundle\Provider;

use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;

class ChannelType implements ChannelInterface
{
    const TYPE = 'training_user_naming';

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'training.usernaming.usernamingtype.channel_type.label';
    }
}