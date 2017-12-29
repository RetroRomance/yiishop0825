<?php

use yii\db\Migration;

class m171229_030647_menu_table extends Migration
{
    public function up()
    {
        $this->createTable('menu',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'parent_id'=>$this->integer()->comment('上级分类'),
            'url'=>$this->string(50)->notNull()->comment('路由地址'),
            'sn'=>$this->integer()->notNull()->comment('排序')
        ]);

    }

    public function down()
    {
        echo "m171229_030647_menu_table cannot be reverted.\n";

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
