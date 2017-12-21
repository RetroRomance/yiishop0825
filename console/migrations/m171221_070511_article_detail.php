<?php

use yii\db\Migration;

class m171221_070511_article_detail extends Migration
{
    public function up()
    {
        $this->createTable('article_detail',[
            'article_id'=>$this->primaryKey()->comment('文章id'),
            'content'=>$this->string()->notNull()->comment('简介')
        ]);

    }

    public function down()
    {
        echo "m171221_070511_article_detail cannot be reverted.\n";

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
