<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 */
class Profile extends \yii\db\ActiveRecord
{
    public $password;
    public $_role;
    public $_user;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function scenarios()
    {
        return [
            'admin_update' => ['username', 'email', 'status', '_role'],
            'admin_create' => ['username', 'email', 'status', '_role'],
            'meupdate' => ['username', 'email', 'password'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at','status'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password', 'validatePassword'],

            ['_role', 'required'],
            [['_role'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUserById($this->id);

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }
    
    /**
     * Finds user by [[id]]
     *
     * @return User|null
     */
    public function getUserById($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUserByUsername($username)
    {
        if (($model = User::findByUsername($username)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // public static function findOne($condition) {
    //     $profile = parent::findOne($condition);
    //     if ($profile->id > 0) {
    //         $profile->setUser(User::findOne(['id' => $profile->id]));
    //     }
    //     return $profile;
    // }

    // public function fg()
    // {
    //     // $this->_user = getUserById
    //     // $this->role = $this->username;
    // }

    public function createProfile() {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = $this->status;
            $user->setPassword($this->username);
            $user->generateAuthKey();

            if ($user->save(false)) {
                $this->id = $user->id;
                // las siguientes tres líneas fueron agregadas
                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole($this->role);
                $auth->assign($authorRole, $user->getId());
            }

            return true;
        }
        return false;
    }

    public function setUser($_user) {
        $this->_user = $_user;
        // $this->role = \Yii::$app->authManager->getRolesByUser($_user->id);
    }

    public function getRole() {
        $roles = \Yii::$app->authManager->getRolesByUser($this->id);
        return $roles[0]['name'];
    }

    public function getRoles() {
        return \Yii::$app->authManager->getRolesByUser($this->id);
    }

    // public function getRole() {
    //     return "OK";
    // }

    // function afterSave($insert, $changedAttributes)
    // {
        
    // }

    // function beforeSave($insert)
    // {
    //     if (empty($this->password_hash)) Yii::$app->security->generatePasswordHash($this->username);
    //     // $insert->generateAuthKey();
    //     // return $insert;
    //     return false;
    // }
}
