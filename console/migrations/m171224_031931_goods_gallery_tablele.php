<?php

use yii\db\Migration;

class m171224_031931_goods_gallery_tablele extends Migration
{
    public function up()
    {
        $this->createTable('goods_gallery',[
            'id'=>$this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'path'=>$this->string()->comment('图片地址')
            ]);

    }

    public function down()
    {
        echo "m171224_031931_goods_gallery_tablele cannot be reverted.\n";

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
