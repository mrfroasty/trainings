<?php

namespace Training\Bundle\UserNamingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\IntegrationBundle\Entity\Channel as Integration;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

use Training\Bundle\UserNamingBundle\Model\ExtendedUserNamingType;

/**
 * @ORM\Entity
 * @ORM\Table(name="training_usernaming_type",
 *     indexes={
 *          @ORM\Index(name="training_username_type_format_idx", columns={"format"}),
 * })
 * @Config(
 *      routeName="training_user_naming_index",
 *      routeView="training_user_naming_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="fa-child"
 *          },
 *          "grid"={
 *              "default"="training-user-naming-types-grid"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"="",
 *              "category"="training_usernaming"
 *          },
 *      }
 * )
 * @package Training\Bundle\UserNamingBundle\Entity
 */
class UserNamingType extends ExtendedUserNamingType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=10
     *          }
     *      }
     * )
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=64)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=20
     *          }
     *      }
     * )
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(name="format", type="string", length=255, nullable=false)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true,
     *              "order"=30
     *          }
     *      }
     * )
     */
    protected $format;

    /**
     * @var string
     * @ORM\Column(name="example", type="string", length=255)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true,
     *              "order"=40
     *          }
     *      }
     * )
     */
    protected $example;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return UserNamingType
     */
    public function setTitle(string $title): UserNamingType
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return UserNamingType
     */
    public function setFormat(string $format): UserNamingType
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * @param string $example
     * @return UserNamingType
     */
    public function setExample(string $example): UserNamingType
    {
        $this->example = $example;

        return $this;
    }
}
