<?php

use yii\db\Migration;

class m171222_024004_goods_category extends Migration
{
    public function up()
    {
        $this->createTable('goods_category',[
            'id'=>$this->primaryKey(),
            'tree'=>$this->integer()->comment('树id'),
            'lft'=>$this->integer()->notNull()->comment('左值'),
            'rgt'=>$this->integer()->comment('右值'),
            'depth'=>$this->integer()->comment('层次'),
            'parent_id'=>$this->integer()->comment('上级分类'),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介')
        ]);

    }

    public function down()
    {
        echo "m171222_024004_goods_category cannot be reverted.\n";

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
