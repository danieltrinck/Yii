<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string|null $auth_key
 * @property string|null $access_token
 * @property string|null $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Users extends ActiveRecord implements IdentityInterface
{
    public $username;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public function behaviors(){

        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['username', 'password', 'auth_key', 'access_token'], 'string', 'max' => 255],
            [['password_hash', 'password_reset_token', 'verification_token'], 'string', 'max' => 100],
            [['auth_key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                   => 'ID',
            'username'             => 'Username',
            'password'             => 'Password',
            'auth_key'             => 'Auth Key',
            'access_token'         => 'Access Token',
            'password_hash'        => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'verification_token'   => 'Verification Token',
            'created_at'           => 'Created At',
            'updated_at'           => 'Updated At',
        ];
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function loginByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token
        ]);
    }

      /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findByUserName($username){
        return static::findOne(['username' => $username]);
    }

    public static function findByPasswordResetToken($token){
        if (!static::isPasswordResetTokenValid($token)){
            return null;
        }
        return static::findOne([
           'password_reset_token' => $token
        ]);
    }

     /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

     /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

     /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function generateToken()
    {
        // $payload = [
        //     'user_id' => $user->id,
        //     'username' => $user->username,
        //     'exp' => time() + 3600 // Token expira em 1 hora
        // ];

        // return JWT::encode($payload, 'teste', 'HS256');
        $this->access_token = Yii::$app->security->generateRandomString();
        $this->save(false);
        return $this->access_token;
    }

}
