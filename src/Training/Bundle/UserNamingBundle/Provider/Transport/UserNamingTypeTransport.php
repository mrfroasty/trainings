<?php


namespace Training\Bundle\UserNamingBundle\Provider\Transport;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Exception\InvalidConfigurationException;
use Oro\Bundle\IntegrationBundle\Provider\Rest\Transport\AbstractRestTransport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Training\Bundle\UserNamingBundle\Form\Type\TransportSettingsFormType;

class UserNamingTypeTransport extends AbstractRestTransport
{
    /**
     * @param Transport $transportEntity
     */
    public function init(Transport $transportEntity)
    {
        // TODO: Implement init() method.
    }

    /**
     * Returns label for UI
     *
     * @return string
     */
    public function getLabel()
    {
        return 'training.usernaming.usernamingtype.transport.label';
    }

    /**
     * Returns form type name needed to setup transport
     *
     * @return string
     */
    public function getSettingsFormType()
    {
        return TransportSettingsFormType::class;
    }

    /**
     * Returns entity name needed to store transport settings
     *
     * @return string
     */
    public function getSettingsEntityFQCN()
    {
        return \Training\Bundle\UserNamingBundle\Entity\UserNamingTypeTransport::class;
    }

    /**
     * Get REST client base url
     *
     * @param ParameterBag $parameterBag
     * @return string
     * @throws InvalidConfigurationException
     */
    protected function getClientBaseUrl(ParameterBag $parameterBag)
    {
        // TODO: Implement getClientBaseUrl() method.
    }

    /**
     * Get REST client options
     *
     * @param ParameterBag $parameterBag
     * @return array
     * @throws InvalidConfigurationException
     */
    protected function getClientOptions(ParameterBag $parameterBag)
    {
        // TODO: Implement getClientOptions() method.
    }
}
