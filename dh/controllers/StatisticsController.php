<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use dh\models\User;
use dh\models\UserLog;
use dh\models\UserSign;
use dh\models\Category;
use dh\models\Website;

class StatisticsController extends Controller {

    public function actionUser() {

        $start = strtotime('-1 month +1 days');
        $end = strtotime('today') + 86399;

        if (Yii::$app->request->get('range')) {
            $range = explode('至', Yii::$app->request->get('range'));
            $start = isset($range[0]) ? strtotime($range[0]) : $start;
            $end = isset($range[1]) && (strtotime($range[1]) < $end) ? strtotime($range[1]) + 86399 : $end;
        }
        $series = [];

        //用户趋势
        $day_signup = User::get_day_total('created_at', $start, $end);
        $day_log = UserLog::get_day_total('created_at', $start, $end);
        $day_sign = UserSign::get_day_total('created_at', $start, $end);

        $data_signup = [];
        $data_log = [];
        $data_sign = [];

        for ($i = $start; $i < $end; $i = $i + 86400) {
            $j = date('Y-m-d', $i);
            $data_signup[] = ['name' => $j, 'y' => isset($day_signup[$j]) ? (int) $day_signup[$j] : 0];
            $data_log[] = ['name' => $j, 'y' => isset($day_log[$j]) ? (int) $day_log[$j] : 0];
            $data_sign[] = ['name' => $j, 'y' => isset($day_sign[$j]) ? (int) $day_sign[$j] : 0];
        }
        $series['day'][] = ['type' => 'line', 'name' => '注册', 'data' => $data_signup];
        $series['day'][] = ['type' => 'line', 'name' => '登录', 'data' => $data_log];
        $series['day'][] = ['type' => 'line', 'name' => '签到', 'data' => $data_sign];


        return $this->render('user', ['series' => $series, 'start' => $start, 'end' => $end]);
    }

    public function actionWebsite() {

        $start = strtotime('-1 month +1 days');
        $end = strtotime('today') + 86399;

        if (Yii::$app->request->get('range')) {
            $range = explode('至', Yii::$app->request->get('range'));
            $start = isset($range[0]) ? strtotime($range[0]) : $start;
            $end = isset($range[1]) && (strtotime($range[1]) < $end) ? strtotime($range[1]) + 86399 : $end;
        }
        $series = [];

        //网址趋势
        $day_category_user = Category::get_day_total('created_at', $start, $end, NULL, 'not');

        $day_website_user = Website::get_day_total('created_at', $start, $end, NULL, 'not');
        $day_website_site = Website::get_day_total('created_at', $start, $end);

//        $day_website_add = UserSign::get_day_total('created_at', $start, $end);
//        $day_website_collect = UserSign::get_day_total('created_at', $start, $end);
//        $day_website_share = UserSign::get_day_total('created_at', $start, $end);

        $data_category_user = [];
        $data_website_user = [];
        $data_website_site = [];

        for ($i = $start; $i < $end; $i = $i + 86400) {
            $j = date('Y-m-d', $i);
            $data_category_user[] = ['name' => $j, 'y' => isset($day_category_user[$j]) ? (int) $day_category_user[$j] : 0];
            $data_website_user[] = ['name' => $j, 'y' => isset($day_website_user[$j]) ? (int) $day_website_user[$j] : 0];
            $data_website_site[] = ['name' => $j, 'y' => isset($day_website_site[$j]) ? (int) $day_website_site[$j] : 0];
        }
        $series['day'][] = ['type' => 'line', 'name' => '用户分类', 'data' => $data_category_user];
        $series['day'][] = ['type' => 'line', 'name' => '用户网址', 'data' => $data_website_user];
        $series['day'][] = ['type' => 'line', 'name' => '本站网址', 'data' => $data_website_site];


        return $this->render('website', ['series' => $series, 'start' => $start, 'end' => $end]);
    }

}
