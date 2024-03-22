<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $nome
 * @property float $preco
 * @property string|null $foto
 * @property int|null $clients_id
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Clients $clients
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['nome'         ,'required'     ,'message' => 'O campo Nome é obrigatório'],
            ['preco'        ,'number'],
            [['clients_id'  ,'created_at'   ,'updated_at'], 'integer'],
            ['nome'         ,'string'       ,'max' => 255 , 'message' => 'O nome permite no máximo 255 caracteres'],
            ['clients_id'   ,'required'     ,'message' => 'O campo clients_id é obrigatório'],
            [['foto']       ,'file'         ,'extensions' => 'png, jpg, png', 'wrongExtension' => 'Apenas arquivos com extensão .png, .jpg, .png são permitidos'],
            [['clients_id'] ,'exist'        ,'skipOnError' => true, 'targetClass' => Clients::class, 'targetAttribute' => ['clients_id' => 'id'], 'message' => 'O campo clients_id precisa fazer referência a um cliente cadastrado.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'nome'        => 'Nome',
            'preco'       => 'Preco',
            'foto'        => 'Foto',
            'clients_id'  => 'Clients ID',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Clients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasOne(Clients::class, ['id' => 'clients_id']);
    }


    public function upload()
    {
        if ($this->validate()) {
            $this->foto->saveAs('uploads/products/' . $this->foto->baseName . '.' . $this->foto->extension);
            return true;
        } else {
            return false;
        }
    }
}
