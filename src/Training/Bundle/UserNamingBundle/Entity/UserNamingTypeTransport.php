<?php

namespace Training\Bundle\UserNamingBundle\Entity;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Symfony\Component\HttpFoundation\ParameterBag;

class UserNamingTypeTransport extends Transport
{
    private $settings;

    /**
     * @var string
     *
     * @ORM\Column(name="training_username_type_url", type="string", length=255, nullable=false)
     */
    protected $url;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return UserNamingTypeTransport
     */
    public function setUrl(string $url): UserNamingTypeTransport
    {
        $this->url = $url;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsBag()
    {
        if (null === $this->settings) {
            $this->settings = new ParameterBag(
                array(
                    'url' => $this->getUrl(),
                )
            );
        }
        return $this->settings;
    }
}