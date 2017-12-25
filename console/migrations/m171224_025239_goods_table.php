<?php

use yii\db\Migration;

class m171224_025239_goods_table extends Migration
{
    public function up()
    {
        $this->createTable('goods',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string(30)->comment('商品名称'),
            'sn'=>$this->integer()->notNull()->comment('货号'),
            'logo'=>$this->string()->comment('logo图片'),
            'goods_category_id'=>$this->integer()->comment('商品分类id'),
            'brand_id'=>$this->integer()->comment('brand_id'),
            'market_price'=>$this->decimal()->notNull()->comment('市场价'),
            'shop_price'=>$this->decimal()->notNull()->comment('商场价'),
            'stock'=>$this->integer()->notNull()->comment('库存'),
            'is_on_sale'=>$this->integer(1)->notNull()->comment('是否在售'),
            'status'=>$this->integer()->notNull()->comment('状态(1正常 0回收站)'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
            'create_time'=>$this->dateTime()->notNull()->comment('添加时间'),
            'view_times'=>$this->integer()->notNull()->comment('浏览次数')
        ]);

    }

    public function down()
    {
        echo "m171224_025239_goods_table cannot be reverted.\n";

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
