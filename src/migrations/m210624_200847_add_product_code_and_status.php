<?php

namespace lukehopkins\ordersamples\migrations;

use Craft;
use craft\db\Migration;

/**
 * m210624_200847_add_product_code_and_status migration.
 */
class m210624_200847_add_product_code_and_status extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Place migration code here...
        $this->addColumn('sample_orders', 'product_code', 'string');
        $this->addColumn('sample_orders', 'status', [
            'column' => 'string',
            'default' => 'New'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210624_200847_add_product_code_and_status cannot be reverted.\n";
        return false;
    }
}
