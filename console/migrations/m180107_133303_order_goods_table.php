<?php

use yii\db\Migration;

class m180107_133303_order_goods_table extends Migration
{
    public function up()
    {
        $this->createTable('order_goods',[
            'id'=>$this->primaryKey(),
            'order_id'=>$this->integer()->comment('订单id'),
            'goods_id'=>$this->integer()->comment('商品id'),
            'goods_name'=>$this->string(255)->comment('商品名称'),
            'logo'=>$this->string(255)->comment('logo图片'),
            'price'=>$this->decimal()->comment('价格'),
            'amount'=>$this->integer()->comment('数量'),
            'total'=>$this->decimal()->comment('小计')
        ]);
    }

    public function down()
    {
        echo "m180107_133303_order_goods_table cannot be reverted.\n";

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
