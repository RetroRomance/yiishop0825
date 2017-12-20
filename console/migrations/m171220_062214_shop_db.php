<?php

use yii\db\Migration;

class m171220_062214_shop_db extends Migration
{
    public function up()
    {
        $this->createTable('brand',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'logo'=>$this->string()->comment('logo图片格'),
            'sort'=>$this->integer(11)->notNull()->comment('排序'),
            'status'=>$this->integer(2)->comment('状态(-1删除 0隐藏 1正常)')
        ]);

    }

    public function down()
    {
        echo "m171220_062214_shop_db cannot be reverted.\n";

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
