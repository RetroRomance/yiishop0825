<?php

use yii\db\Migration;

class m171226_023043_alter_goods_sn extends Migration
{
    public function up()
    {
        $this->alterColumn('goods','sn','string(20)');
    }

    public function down()
    {
        echo "m171226_023043_alter_goods_sn cannot be reverted.\n";

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
