<?php

use yii\db\Migration;

class m171221_070038_article extends Migration
{
    public function up()
    {
        $this->createTable('article',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'article_category_id'=>$this->integer()->comment('文章分类id'),
            'sort'=>$this->integer(11)->notNull()->comment('排序'),
            'status'=>$this->integer(2)->comment('状态(-1删除 0隐藏 1正常)'),
            'create_time'=>$this->dateTime()->comment('创建时间')
        ]);

    }

    public function down()
    {
        echo "m171221_070038_article cannot be reverted.\n";

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
