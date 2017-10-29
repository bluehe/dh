<?php

namespace dh\models;

use Yii;
use yii\base\Model;

//use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $email;
    public $tel;
    public $password;
    public $password1;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'email', 'tel'], 'trim'],
            ['username', 'required', 'message' => '{attribute}不能为空'],
            [['username', 'email', 'tel'], 'unique', 'targetClass' => '\dh\models\User', 'message' => '{attribute}已经存在'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email', 'message' => '{attribute}格式不正确'],
            ['email', 'string', 'max' => 255],
            [['password', 'password1'], 'required', 'message' => '密码不能为空'],
            ['password', 'string', 'min' => 4, 'message' => '{attribute}不能少于4个字符'],
            ['password1', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码不一致'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'message' => '{attribute}不正确', 'on' => 'captchaRequired'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => '用户名',
            'email' => '电子邮件',
            'tel' => '联系电话',
            'password' => '密码',
            'password1' => '确认密码',
            'verifyCode' => '验证码'
        );
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->tel = $this->tel;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($user->save()) {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole('member');
            $auth->assign($role, $user->id);
            return $user;
        } else {
            return null;
        }
    }

}
