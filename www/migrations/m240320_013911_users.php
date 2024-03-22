<?php

use yii\db\Migration;

/**
 * Class m240320_013911_users
 */
class m240320_013911_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("users",[
            'id'            => $this->primaryKey(),
            'name'          => $this->string(255)->null(),
            'username'      => $this->string(255)->notNull(),
            'password'      => $this->string(255)->notNull(),
            'auth_Key'      => $this->string(255)->null()->unique(),
            'access_token'  => $this->string(255)->null(),
            'password_hash' => $this->string(255)->null(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("users");
    }

}
