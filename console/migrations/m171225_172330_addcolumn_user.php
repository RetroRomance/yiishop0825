<?php

use yii\db\Migration;

class m171225_172330_addcolumn_user extends Migration
{
    public function up()
    {
        $this->addColumn('user','last_login_time','dateTime');
        $this->addColumn('user','last_login_ip','string');

    }

    public function down()
    {
        echo "m171225_172330_addcolumn_user cannot be reverted.\n";

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
