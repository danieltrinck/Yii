<?php

use yii\db\Migration;

/**
 * Class m240319_232120_products
 */
class m240319_232120_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('products', [
            'id'          => $this->primaryKey(),
            'nome'        => $this->string(255)->notNull(),
            'preco'       => $this->double(0)->notNull()->defaultValue(0),
            'foto'        => $this->string(255)->null(),
            'clients_id'  => $this->integer(),
            'created_at'  => $this->integer(),
            'updated_at'  => $this->integer()
        ]);

        $this->addForeignKey('clients_fk', 'products', 'clients_id', 'clients', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('clients_fk', 'products');
        $this->dropTable('products');
    }

}
