<?php

use yii\db\Migration;

class m171224_031737_goods_intro_table extends Migration
{
    public function up()
    {
        $this->createTable('goods_intro',[
            'goods_id'=>$this->integer()->comment('商品id'),
            'content'=>$this->text()->comment('商品描述')
        ]);

    }

    public function down()
    {
        echo "m171224_031737_goods_intro_table cannot be reverted.\n";

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
