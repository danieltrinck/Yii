<?php

use yii\db\Migration;

/**
 * Class m240319_231559_clients
 */
class m240319_231559_clients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('clients', [
            'id'          => $this->primaryKey(),
            'nome'        => $this->string(255)->notNull(),
            'cpf'         => $this->string(11)->unique()->notNull(),
            'cep'         => $this->string(8)->null(),
            'logradouro'  => $this->string(255)->null(),
            'numero'      => $this->string(10)->null(),
            'cidade'      => $this->string(255)->null(),
            'estado'      => $this->string(2)->null(),
            'complemento' => $this->string(255)->null(),
            'foto'        => $this->string(255)->null(),
            'sexo'        => $this->string(1)->defaultValue('M'),
            'created_at'  => $this->integer(),
            'updated_at'  => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('clients');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240319_231559_clients cannot be reverted.\n";

        return false;
    }
    */
}
