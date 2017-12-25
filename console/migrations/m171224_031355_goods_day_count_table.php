<?php

use yii\db\Migration;

class m171224_031355_goods_day_count_table extends Migration
{
    public function up()
    {
        $this->createTable('goods_day_count',[
            'day'=>$this->dateTime()->comment('日期'),
            'count'=>$this->integer()->comment('商品数')
            ]);

    }

    public function down()
    {
        echo "m171224_031355_goods_day_count_table cannot be reverted.\n";

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
