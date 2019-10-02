<?php

namespace Training\Bundle\UserNamingBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Extend\RelationType;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class TrainingUserNamingBundleInstaller implements Installation, ExtendExtensionAwareInterface
{
    /** @var ExtendExtension */
    protected $extendExtension;

    /**
     * Sets the ExtendExtension
     *
     * @param ExtendExtension $extendExtension
     * @return TrainingUserNamingBundleInstaller
     */
    public function setExtendExtension(ExtendExtension $extendExtension)
    {
        $this->extendExtension = $extendExtension;

        return $this;
    }

    /**
     * Gets a number of the last migration version implemented by this installation script
     *
     * @return string
     */
    public function getMigrationVersion()
    {
        return 'v1_1';
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
        $this->createTrainingUserNamingTypeTable($schema);
        $this->addNamingExtensionType($schema);
    }

    /**
     * Create training_usernaming_type table
     *
     * @param Schema $schema
     */
    protected function createTrainingUserNamingTypeTable(Schema $schema)
    {
        $table = $schema->createTable('training_usernaming_type');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('title', 'string', ['length' => 64, 'notnull' => false]);
        $table->addColumn('format', 'string', ['length' => 255]);
        $table->addIndex(['format'], 'format_idx', []);
        $table->setPrimaryKey(['id']);
    }


    /**
     * @param Schema $schema
     */
    private function addNamingExtensionType(Schema $schema)
    {
        $this->extendExtension->addManyToOneRelation(
            $schema,
            'training_usernaming_type',
            'user',
            'oro_user',
            'id',
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