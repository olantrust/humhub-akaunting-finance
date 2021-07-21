<?php

use yii\db\Migration;

class uninstall extends Migration
{

    public function up()
    {
        // TODO : Write the SQL here to drop tables.
        $this->dropTable('{{%akaunting_company}}');
        // $this->dropTable('{{%akaunting_user}}');
        $this->dropTable('{{%akaunting_company_user}}');
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}
