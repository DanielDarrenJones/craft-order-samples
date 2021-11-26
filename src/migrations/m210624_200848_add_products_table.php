<?php

namespace lukehopkins\ordersamples\migrations;

use Craft;
use craft\db\Migration;

/**
 * m210624_200848_add_products_table migration.
 */
class m210624_200848_add_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Place migration code here...
        $this->createTable('sample_orders_products', [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
            'requestId' => $this->integer()->notNull(),
            'product_code' => $this->string(255)->notNull()->defaultValue(''),
            'product_name' => $this->string(255)->notNull()->defaultValue(''),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210624_200848_add_products_table cannot be reverted.\n";
        return false;
    }
}
