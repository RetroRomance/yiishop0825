<?php

use yii\db\Migration;

class m180102_153904_table_member extends Migration
{
    public function up()
    {
        $this->createTable('member',[
            'id'=>$this->primaryKey(),
            'username'=>$this->string(50)->comment('用户名'),
            'auth_key'=>$this->string(32)->comment('秘钥'),
            'password_hash'=>$this->string(100)->comment('密码'),
            'email'=>$this->string(100)->comment('邮箱'),
            'tel'=>$this->char(11)->comment('电话'),
            'last_login_time'=>$this->dateTime()->comment('最后登录时间'),
            'last_login_ip'=>$this->char()->comment('最后登录IP'),
            'status'=>$this->integer(1)->comment('状态1正常0删除'),
            'created_at'=>$this->dateTime()->comment('添加时间'),
            'updated_at'=>$this->dateTime()->comment('修改时间')
        ]);

    }

    public function down()
    {
        echo "m180102_153904_table_member cannot be reverted.\n";

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
