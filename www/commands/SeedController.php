<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use Yii;
use app\models\Clients;
use app\models\Users;
use \Datetime;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SeedController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @return int Exit code
     */
    public function actionIndex($username='admin',$password='admin',$name='admin')
    {
        $product = [
            [
                'nome'  => 'Garrafa Pet',
                'preco' => '2.99'
            ],
            [
                'nome'  => 'Refrigerante',
                'preco' => '10'
            ],
            [
                'nome'  => 'Suco Natural',
                'preco' => '23.99'
            ],
            [
                'nome'  => 'Danone',
                'preco' => '3'
            ],
        ];

        $clients = [
            [
                'nome'          => 'Daniel Silva Terento',
                'cpf'           => '1234567'.rand(0,999),
                'cep'           => '14403788',
                'logradouro'    => 'Centro',
                'numero'        => 'A25',
                'cidade'        => 'São Paulo',
                'estado'        => 'SP',
                'complemento'   => '',
                'foto'          => '',
                'sexo'          => 'M'
            ],
            [
                'nome'          => 'Maria Dolores Barreto',
                'cpf'           => '1234567'.rand(0,999),
                'cep'           => '14403788',
                'logradouro'    => 'Centro',
                'numero'        => '123',
                'cidade'        => 'Rio de Janeiro',
                'estado'        => 'RJ',
                'complemento'   => 'Ap2',
                'foto'          => '',
                'sexo'          => 'F'
            ],
            [
                'nome'          => 'Joaquim Pereira Cabral',
                'cpf'           => '1234567'.rand(0,999),
                'cep'           => '14401234',
                'logradouro'    => 'Centro',
                'numero'        => '2485',
                'cidade'        => 'São Paulo',
                'estado'        => 'SP',
                'complemento'   => '',
                'foto'          => '',
                'sexo'          => 'M'
            ],
            [
                'nome'          => 'João Machado Baez',
                'cpf'           => '1234567'.rand(0,999),
                'cep'           => '14403700',
                'logradouro'    => 'Centro',
                'numero'        => '547',
                'cidade'        => 'Belo Horizonte',
                'estado'        => 'MG',
                'complemento'   => '',
                'foto'          => '',
                'sexo'          => 'M'
            ],
        ];
        
        $now = new DateTime();
        $now->format('Y-m-d H:i:s');

        $users = Users::find()->where(['username'=>$username])->all();
        if(empty($users))
        {
            $auth_key = Yii::$app->security->generateRandomString();
            $password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
            
            Yii::$app->db->createCommand()->batchInsert('users',
                [
                    'name',
                    'username',
                    'password',
                    'access_token',
                    'auth_Key',
                    'password_hash',
                    'created_at',
                    'updated_at'
                ],[
                [
                    $name,
                    $username, 
                    $password, 
                    '',
                    $auth_key,
                    $password_hash,
                    $now->getTimestamp(),
                    $now->getTimestamp()
                ]
            ])->execute();
        }

        for ($i=0; $i < 4; $i++) { 
            
            Yii::$app->db->createCommand()->batchInsert('clients',
                [
                    'nome',
                    'cpf',
                    'cep',
                    'logradouro',
                    'numero',
                    'cidade',
                    'estado',
                    'complemento',
                    'foto',
                    'sexo',
                    'created_at',
                    'updated_at'
                ],[
                [
                    $clients[$i]['nome'],
                    $clients[$i]['cpf'],
                    $clients[$i]['cep'],
                    $clients[$i]['logradouro'],
                    $clients[$i]['numero'],
                    $clients[$i]['cidade'],
                    $clients[$i]['estado'],
                    $clients[$i]['complemento'],
                    $clients[$i]['foto'],
                    $clients[$i]['sexo'],
                    $now->getTimestamp(),
                    $now->getTimestamp()
                ]
            ])->execute();

            $client_id = Clients::find()->orderBy(['id' => SORT_DESC])->one()->id;

            Yii::$app->db->createCommand()->batchInsert('products',
                [
                    'nome',
                    'preco',
                    'foto',
                    'clients_id',
                    'created_at',
                    'updated_at'
                ],[
                [
                    $product[$i]['nome'],
                    $product[$i]['preco'],
                    '',
                    $client_id,
                    $now->getTimestamp(),
                    $now->getTimestamp()
                ],
            ])->execute();
        }

        return ExitCode::OK;
    }
}
