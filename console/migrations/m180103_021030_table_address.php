<?php

use yii\db\Migration;

class m180103_021030_table_address extends Migration
{
    public function up()
    {
        $this->createTable('address',[
            'id'=>$this->primaryKey(),
            'username'=>$this->string(50)->comment('收货人'),
            'cmbProvince'=>$this->string(50)->comment('省'),
            'cmbCity'=>$this->string(50)->comment('市'),
            'cmbArea'=>$this->string(50)->comment('县/区'),
            'address'=>$this->string(100)->comment('详细地址'),
            'tel'=>$this->char(11)->comment('电话')
        ]);

    }

    public function down()
    {
        echo "m180103_021030_table_address cannot be reverted.\n";

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
