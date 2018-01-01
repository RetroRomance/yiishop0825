<?php

use yii\db\Migration;

class m171229_075734_add_menu extends Migration
{
    public function up()
    {
        $this->addColumn('menu','lft','integer');
        $this->addColumn('menu','rgt','integer');
        $this->addColumn('menu','depth','integer');

    }

    public function down()
    {
        $this->dropColumn('menu','lft');
        $this->dropColumn('menu','rgt');
        $this->dropColumn('menu','depth');
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
