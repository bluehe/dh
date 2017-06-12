<?php

namespace dms\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\UserAuth;
use dms\models\SignupForm;
use dms\models\PasswordResetRequestForm;
use dms\models\ResetPasswordForm;
use dms\models\System;
use dms\models\Forum;
use dms\models\Room;
use dms\models\Bed;
use common\models\User;
use dms\models\RepairOrder;
use yii\data\Pagination;

/**
 * Site controller
 */
class SiteController extends Controller {

    public $attempts = 3; // allowed 3 attempts
    public $counter;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => $captcha_length = System::getValue('captcha_length'), //最大显示个数
                'minLength' => $captcha_length, //最少显示个数
            ],
        ];
    }

    public function successCallback($client) {
        $type = $client->getId(); // qq | weibo | github
        $attributes = $client->getUserAttributes(); // basic info

        $auth = UserAuth::find()->where(['type' => $type, 'open_id' => $attributes['id']])->one();
        switch ($type) {
            case 'github': $avatar = $attributes['avatar_url'];
                break;
            case 'weibo': $avatar = $attributes['profile_image_url'];
                break;
            case 'qq': $avatar = $attributes['figureurl_qq_2'];
                break;
            default:
                $avatar = '';
                break;
        }
        if ($auth) {
//存在
            if (Yii::$app->user->login($auth->user)) {
                if (!$auth->user->avatar) {
                    $auth->user->avatar = $avatar;
                    $auth->user->save();
                }
                return $this->goHome();
            }
        } else {
//不存在，注册
            Yii::$app->session->set('auth_type', $type);
            Yii::$app->session->set('auth_openid', $attributes['id']);
            Yii::$app->session->set('auth_avatar', $avatar);
            return $this->redirect('complete');
        }


// user login or signup comes here
    }

    public function actionComplete() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model_l = new LoginForm();
        $model_s = new SignupForm();
        if (Yii::$app->request->isPost) {

            if (Yii::$app->request->post('type') === 'bind') {
                //登录
                if ($model_l->load(Yii::$app->request->post()) && $model_l->login()) {
                    Yii::$app->session->remove('loginCaptchaRequired');
                    //创建第三方记录
                    $auth = new UserAuth();
                    $auth->type = Yii::$app->session->get('auth_type');
                    $auth->open_id = Yii::$app->session->get('auth_openid');
                    $auth->uid = Yii::$app->user->identity->id;
                    $auth->created_at = time();
                    if ($auth->save()) {
                        if (!$auth->user->avatar) {
                            $auth->user->avatar = Yii::$app->session->get('auth_avatar');
                            $auth->user->save();
                        }
                        Yii::$app->session->remove('auth_type');
                        Yii::$app->session->remove('auth_openid');
                        Yii::$app->session->remove('auth_avatar');
                        return $this->goHome();
                    }
                } else {
                    $this->counter = Yii::$app->session->get('loginCaptchaRequired') + 1;
                    Yii::$app->session->set('loginCaptchaRequired', $this->counter);
                }
            } else {
                if ($model_s->load(Yii::$app->request->post())) {
                    if (Yii::$app->request->isAjax) {
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return \yii\bootstrap\ActiveForm::validate($model_s);
                    }
                    //创建用户
                    if ($user = $model_s->signup()) {
                        //登录
                        if (Yii::$app->getUser()->login($user)) {
                            //创建第三方记录
                            $auth = new UserAuth();
                            $auth->type = Yii::$app->session->get('auth_type');
                            $auth->open_id = Yii::$app->session->get('auth_openid');
                            $auth->uid = Yii::$app->user->identity->id;
                            $auth->created_at = time();
                            if ($auth->save()) {
                                if (!$auth->user->avatar) {
                                    $auth->user->avatar = Yii::$app->session->get('auth_avatar');
                                    $auth->user->save();
                                }
                                Yii::$app->session->remove('auth_type');
                                Yii::$app->session->remove('auth_openid');
                                Yii::$app->session->remove('auth_avatar');
                                return $this->goHome();
                            }
                        }
                    }
                }
            }
        }
        $this->counter = Yii::$app->session->get('loginCaptchaRequired') + 1;
        $captcha_loginfail = System::getValue('captcha_loginfail');
        if ((($this->counter > $this->attempts && $captcha_loginfail == '1') || $captcha_loginfail != '1') && System::existValue('captcha_open', '2')) {
            $model_l->setScenario("captchaRequired");
        }
        if (System::existValue('captcha_open', '1')) {
            $model_s->setScenario("captchaRequired");
        }


        $this->layout = '//main-login';
        return $this->render('complete', ['model_l' => $model_l, 'model_s' => $model_s,]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $total = [];
        if (Yii::$app->user->can('楼苑设置')) {
            $forum_fup = Forum::find()->where(['not', ['fup' => NULL]])->select(['fup'])->column();

            $total['building'] = Forum::find()->where(['not', ['id' => $forum_fup]])->count();
            $total['broom'] = Room::find()->where(['rid' => NULL])->count();
            $total['sroom'] = Room::find()->where(['not', ['rid' => NULL]])->count();
            $total['bed'] = Bed::find()->count();
            $total['user'] = User::find()->count();
        }
        if (Yii::$app->user->can('维修管理')) {
            $total['repair_today'] = RepairOrder::get_repair_today(strtotime(date('Y-m-d', time())));
            $total['repair'] = RepairOrder::get_stat_total();
        }
        // 创建一个 DB 查询来获得所有 status 为 1 的文章
        $query = Bed::find()->where(['stat' => Bed::STAT_OPEN]);
// 得到文章的总数（但是还没有从数据库取数据）
        $count = $query->count();

// 使用总数来创建一个分页对象
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 5]);

// 使用分页对象来填充 limit 子句并取得文章数据
        $beds = $query->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
        return $this->render('index', ['total' => $total, 'beds' => $beds, 'pagination' => $pagination]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                Yii::$app->session->remove('loginCaptchaRequired');
                return $this->goBack();
            } else {
                $this->counter = Yii::$app->session->get('loginCaptchaRequired') + 1;
                Yii::$app->session->set('loginCaptchaRequired', $this->counter);
            }
        }
        $this->counter = Yii::$app->session->get('loginCaptchaRequired') + 1;
        $captcha_loginfail = System::getValue('captcha_loginfail');
        if ((($this->counter > $this->attempts && $captcha_loginfail == '1') || $captcha_loginfail != '1') && System::existValue('captcha_open', '2')) {
            $model->setScenario("captchaRequired");
        }

        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return \yii\bootstrap\ActiveForm::validate($model);
            }
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        } else {
            if (System::existValue('captcha_open', '1')) {
                $model->setScenario("captchaRequired");
            }
        }
        $this->layout = '//main-login';
        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        $model->load($_POST);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', '邮件已经发送，请检查你的邮件并进一步操作。');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '对不起，我们不能通过你提供的邮箱进行密码重置。');
            }
        } else {
            if (System::existValue('captcha_open', '3')) {
                $model->setScenario("captchaRequired");
            }
        }
        $this->layout = '//main-login';
        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            Yii::$app->session->setFlash('danger', '链接已过期，请重新操作。');

            return $this->goHome();
//throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', '新密码已经被保存。');

            return $this->goHome();
        }
        $this->layout = '//main-login';
        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

}
