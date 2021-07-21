<?php

use yii\db\Migration;

/**
 * Class m210126_133404_create_akaunting_link_tables
 */
class m210126_133404_create_akaunting_link_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 ENGINE=InnoDB';
        }

        $this->createTable('{{%akaunting_company}}', [
            'id'         => $this->primaryKey(),
            'space_id'   => $this->integer()->comment('HH Space ID, Foreign Key reference space.id'),
            'akc_id'     => $this->integer()->comment('Akaunting company ID'),
            'akc_name'   => $this->text()->comment('Akaunting company name'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('akaunting_company_akc_id_idx', '{{%akaunting_company}}', 'akc_id');
        $this->createIndex('akaunting_company_space_id_idx', '{{%akaunting_company}}', 'space_id');
        $this->addForeignKey('akaunting_company_space_id_fk', '{{%akaunting_company}}', 'space_id', '{{%space}}', 'id', 'CASCADE', 'CASCADE');

        // $this->createTable('{{%akaunting_user}}', [
        //     'id'           => $this->primaryKey(),
        //     'user_id'      => $this->integer()->comment('HH User ID, Foreign key references user.id'),
        //     'akc_id'       => $this->integer()->comment('Akaunting company ID'),
        //     'aku_id'       => $this->integer()->comment('Akaunting user ID'),
        //     'aku_password' => $this->string()->comment('Encrypted Password to login to Akaunting'),
        //     'created_at'   => $this->integer(),
        //     'updated_at'   => $this->integer(),
        // ], $tableOptions);

        // $this->createIndex('akaunting_user_aku_id_idx', '{{%akaunting_user}}', 'aku_id');
        // $this->createIndex('akaunting_user_user_id_idx', '{{%akaunting_user}}', 'user_id');
        // $this->addForeignKey('akaunting_user_user_id_fk', '{{%akaunting_user}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%akaunting_company_user}}', [
            'id'           => $this->primaryKey(),
            'space_id'     => $this->integer()->comment('HH Space ID, Foreign Key reference space.id'),
            'akc_id'       => $this->integer()->comment('Akaunting company ID'),
            'user_id'      => $this->integer()->comment('HH User ID, Foreign key references user.id'),
            'aku_id'       => $this->integer()->comment('Akaunting user ID'),
            'aku_password' => $this->string()->comment('Encrypted Password to login to Akaunting'),
            'created_at'   => $this->integer(),
            'updated_at'   => $this->integer(),
        ], $tableOptions);

        $this->createIndex('akaunting_company_user_space_id_idx', '{{%akaunting_company_user}}', 'space_id');
        $this->createIndex('akaunting_company_user_akc_id_idx', '{{%akaunting_company_user}}', 'akc_id');

        $this->createIndex('akaunting_company_user_user_id_idx', '{{%akaunting_company_user}}', 'user_id');
        $this->createIndex('akaunting_company_user_aku_id_idx', '{{%akaunting_company_user}}', 'aku_id');

        $this->addForeignKey('akaunting_company_user_space_id_fk', '{{%akaunting_company_user}}', 'space_id', '{{%space}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('akaunting_company_user_user_id_fk', '{{%akaunting_company_user}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->dropTable('{{%akaunting_company}}');
        // $this->dropTable('{{%akaunting_user}}');
        // $this->dropTable('{{%akaunting_company_user}}');

        echo "m210126_133404_create_akaunting_link_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210126_133404_create_akaunting_link_tables cannot be reverted.\n";

        return false;
    }
    */
}
