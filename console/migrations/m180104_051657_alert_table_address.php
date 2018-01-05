<?php

use yii\db\Migration;

class m180104_051657_alert_table_address extends Migration
{
    public function up()
    {
        $this->addColumn('address','member_id','integer');
        $this->addColumn('address','sort','integer');

    }

    public function down()
    {
        echo "m180104_051657_alert_table_address cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
