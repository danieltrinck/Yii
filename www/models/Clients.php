<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property string $nome
 * @property string $cpf
 * @property string|null $cep
 * @property string|null $logradouro
 * @property string|null $numero
 * @property string|null $cidade
 * @property string|null $estado
 * @property string|null $complemento
 * @property string|null $foto
 * @property string|null $sexo
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Products[] $products
 */
class Clients extends \yii\db\ActiveRecord
{

    /**
    * {@inheritdoc}
    */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['nome'        ,'required'     ,'message' => 'O campo Nome é obrigatório'],
            ['cpf'         ,'required'     ,'message' => 'O campo CPF é obrigatório'],
            ['cpf'         ,'string'       ,'max'     => 11 , 'message' => 'O CPF tem no máximo 11 números'],
            ['cpf'         ,'unique'                        , 'message' => 'O campo CPF já está sendo utilizado'],
            ['cpf'         ,'validateCpf'                   , 'message' => 'CPF inválido'],
            [['nome'       ,'logradouro'   ,'cidade'        , 'complemento'], 'string', 'max' => 255, 'message' => 'Os campos nome, logradouro, cidade e complemento só permitem no máximo 255 caracters'],
            ['cep'         ,'string'       ,'max'     => 8  , 'message' => 'O CEP tem no máximo 8 números'],
            ['numero'      ,'string'       ,'max'     => 10 , 'message' => 'O número precisa ser menor que 10 caracteres'],
            ['estado'      ,'string'       ,'max'     => 2  , 'message' => 'O estado deve conter apenas 2 caracteres. SP'],
            ['sexo'        ,'string'       ,'max'     => 1  , 'message' => 'O campo sexo deve ser apenas M ou F'],
            [['created_at' ,'updated_at']  ,'integer' ],
            ['foto'        ,'file'         ,'extensions' => 'png, jpg, png', 'wrongExtension' => 'Apenas arquivos com extensão .png, .jpg, .png são permitidos'],
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
            'cpf'         => 'Cpf',
            'cep'         => 'Cep',
            'logradouro'  => 'Logradouro',
            'numero'      => 'Numero',
            'cidade'      => 'Cidade',
            'estado'      => 'Estado',
            'complemento' => 'Complemento',
            'foto'        => 'Foto',
            'sexo'        => 'Sexo',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['clients_id' => 'id']);
    }

    // Função de validação personalizada para CPF
    public function validateCpf($attribute, $params)
    {
        // Remove caracteres não numéricos do CPF
        $cpf = preg_replace('/[^0-9]/', '', $this->$attribute);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            $this->addError($attribute, 'CPF inválido.');
            return;
        }

        // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $this->addError($attribute, 'CPF inválido.');
            return;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $this->addError($attribute, 'CPF inválido.');
                return;
            }
        }
        return true;
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->foto->saveAs('uploads/clients/' . $this->foto->baseName . '.' . $this->foto->extension);
            return true;
        } else {
            return false;
        }
    }

}
