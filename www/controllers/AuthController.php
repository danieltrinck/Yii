<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use Firebase\JWT\JWT;
use app\models\Users; // Substitua pelo modelo de usuário correto
use yii\helpers\Security;

class AuthController extends Controller
{
    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        $user = Users::findByUsername($username);

        if ($user && $user->validatePassword($password)) {
            $token = $user->generateToken();
            Yii::$app->response->statusCode = 200;
            return ['token' => $token];
        } else {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Unauthorized'];
        }
    }
}