<?php


namespace Training\Bundle\UserNamingBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class TrainingUserNamingBundle implements Migration
{
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
}