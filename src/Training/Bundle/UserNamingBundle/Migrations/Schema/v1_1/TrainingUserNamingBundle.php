<?php


namespace Training\Bundle\UserNamingBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Extend\RelationType;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class TrainingUserNamingBundle implements Migration, ExtendExtensionAwareInterface
{
    /** @var ExtendExtension */
    protected $extendExtension;

    /**
     * @inheritDoc
     */
    public function setExtendExtension(ExtendExtension $extendExtension)
    {
        $this->extendExtension = $extendExtension;

        return $this;
    }

    /**
     * Modifies the given schema to apply necessary changes of a database
     * The given query bag can be used to apply additional SQL queries before and after schema changes
     *
     * @param Schema $schema
     * @param QueryBag $queries
     * @return void
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addNamingExtensionType($schema);
    }

    /**
     * @param Schema $schema
     */
    private function addNamingExtensionType(Schema $schema)
    {
        $this->extendExtension->addManyToOneRelation(
            $schema,
            'oro_user',
            'usernaming_type',
            'training_usernaming_type',
            'title',
            [
                'extend' => [
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['all'],
                    'on_delete' => 'CASCADE',
                    'nullable' => true
                ]
            ],
            RelationType::MANY_TO_ONE
        );
    }



}
