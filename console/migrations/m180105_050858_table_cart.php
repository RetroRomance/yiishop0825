<?php

use yii\db\Migration;

class m180105_050858_table_cart extends Migration
{
    public function up()
    {
        $this->createTable('cart',[
            'id'=>$this->primaryKey(),
            'goods_id'=>$this->integer()->comment('用户id'),
            'amount'=>$this->integer()->comment('商品数量'),
            'member_id'=>$this->integer()->comment('用户id')
        ]);

    }

    public function down()
    {
        echo "m180105_050858_table_cart cannot be reverted.\n";

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
