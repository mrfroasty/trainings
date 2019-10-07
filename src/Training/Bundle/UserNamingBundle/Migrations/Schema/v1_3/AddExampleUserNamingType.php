<?php


namespace Training\Bundle\UserNamingBundle\Migrations\Schema\v1_3;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class AddExampleUserNamingType implements Migration
{
    /**
     * @inheritDoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addExampleColumnToUserNamingType($schema);
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function addExampleColumnToUserNamingType(Schema $schema)
    {
        $table = $schema->getTable('training_usernaming_type');
        $table->addColumn('example', 'string', ['notnull' => false, 'length' => 255]);
    }
}