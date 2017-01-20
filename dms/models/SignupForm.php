<?php

namespace dms\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $email;
    public $password;
    public $password1;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => '用户名不能为空'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '用户名已经存在'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required', 'message' => 'E-mail不能为空'],
            ['email', 'email', 'message' => 'E-mail格式不正确'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'E-mail已经存在'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'E-mail已经被使用'],
            [['password', 'password1'], 'required', 'message' => '密码不能为空'],
            ['password', 'string', 'min' => 4, 'message' => '密码不能少于4个字符'],
            ['password1', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码不一致'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'message' => '验证码不正确', 'on' => 'captchaRequired'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => '用户名',
            'email' => '电子邮件',
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
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

}
