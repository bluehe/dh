<?php

namespace dh\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $tel
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'auth_key', 'password_hash'], 'required'],
            [['point', 'status', 'created_at', 'updated_at', 'plate'], 'integer'],
            [['username', 'email', 'tel', 'nickname'], 'unique', 'message' => '{attribute}已经存在'],
            [['username', 'nickname'], 'string', 'max' => 32],
            [['username'], 'string', 'min' => 4],
            [['nickname'], 'string', 'min' => 4],
            [['password_hash', 'password_reset_token', 'email', 'tel', 'avatar'], 'string', 'max' => 255],
            [['skin'], 'string', 'max' => 16],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '密码',
            'password_hash' => '密码Hash',
            'password_reset_token' => '重置密码token',
            'nickname' => '昵称',
            'email' => 'Email',
            'tel' => '电话',
            'avatar' => '头像',
            'plate' => '版式',
            'skin' => '皮肤',
            'point' => '积分',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }

    public static $List = [
        'status' => [
            self::STATUS_ACTIVE => "正常",
            self::STATUS_DELETED => "删除"
        ]
    ];

    public function getStatus() {
        $status = isset(self::$List['status'][$this->status]) ? self::$List['status'][$this->status] : null;
        return $status;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories() {
        return $this->hasMany(Category::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAttens() {
        return $this->hasMany(UserAtten::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAttens0() {
        return $this->hasMany(UserAtten::className(), ['user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuths() {
        return $this->hasMany(UserAuth::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLogs() {
        return $this->hasMany(UserLog::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPoints() {
        return $this->hasMany(UserPoint::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSigns() {
        return $this->hasMany(UserSign::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsiteClicks() {
        return $this->hasMany(WebsiteClick::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsiteReports() {
        return $this->hasMany(WebsiteReport::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsiteReports0() {
        return $this->hasMany(WebsiteReport::className(), ['check_uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsiteShares() {
        return $this->hasMany(WebsiteShare::className(), ['uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsiteShares0() {
        return $this->hasMany(WebsiteShare::className(), ['check_uid' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public static function get_tab_useradd($num = '') {
        $query = static::find()->andWhere(['status' => self::STATUS_ACTIVE])->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC]);
        if ($num) {
            $query->limit($num);
        }
        $data = [];
        foreach ($query->each() as $user) {
            $data[] = ['template_id' => 'user', 'url' => Url::toRoute(['site/people', 'id' => $user->id]), 'title' => $user->nickname ? $user->nickname : $user->username, 'label' => Yii::$app->formatter->asRelativeTime($user->created_at), 'img' => Html::img($user->avatar ? $user->avatar : '@web/image/user.png', ['class' => 'img-circle'])];
        }
        return $data;
    }

    public static function get_avatar($id) {
        $user = static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        return $user && $user->avatar ? $user->avatar : '@web/image/user.png';
    }

    public static function get_nickname($id) {
        $user = static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        return $user ? ($user->nickname ? $user->nickname : $user->username) : '';
    }

    public static function exist_nickname($nickname) {
        $user = static::findOne(['nickname' => $nickname, 'status' => self::STATUS_ACTIVE]);
        return $user ? $user->id : false;
    }

    public static function get_day_total($a = 'created_at', $start = '', $end = '') {

        $query = static::find()->where(['status' => self::STATUS_ACTIVE])->andFilterWhere(['>=', $a, $start])->andFilterWhere(['<=', $a, $end]);
        return $query->groupBy(["FROM_UNIXTIME($a, '%Y-%m-%d')"])->select(['count(*)', "FROM_UNIXTIME($a,'%Y-%m-%d')"])->indexBy("FROM_UNIXTIME($a,'%Y-%m-%d')")->column();
    }

}
