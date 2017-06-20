<?php

namespace dms\controllers;

use Yii;
use yii\web\Controller;
use dms\models\RepairOrder;
use dms\models\RepairWorker;
use dms\models\Room;
use dms\models\Pickup;

class StatisticsController extends Controller {

    public function actionRepair() {

        $start = strtotime('-1 month +1 days');
        $end = strtotime('today') + 86399;

        if (Yii::$app->request->get('range')) {
            $range = explode('至', Yii::$app->request->get('range'));
            $start = isset($range[0]) ? strtotime($range[0]) : $start;
            $end = isset($range[1]) && (strtotime($range[1]) < $end) ? strtotime($range[1]) + 86399 : $end;
        }


        $model = new RepairOrder();

        $types = RepairWorker::get_type_id();
        $areas = Room::get_forum_id();

        $series = [];
        //区域合计
        $repair_area = RepairOrder::get_area_total('', $start, $end);

        arsort($repair_area);
        $data = [];
        foreach ($repair_area as $index => $one) {
            $data[] = ['name' => isset($areas[$index]) ? $areas[$index] : '未定义', 'y' => (int) $one];
        }
        $series['area_total'][] = ['type' => 'pie', 'name' => '合计', 'data' => $data];

        //区域类型
        foreach ($types as $key => $type) {
            $r = RepairOrder::get_area_total($key, $start, $end);
            $data = [];
            foreach ($r as $index => $one) {
                $data[] = ['name' => isset($areas[$index]) ? $areas[$index] : '未定义', 'y' => (int) $one];
            }
            $series['area'][] = ['type' => 'column', 'name' => $type, 'data' => $data];
        }
        //未设置类型
        $r = RepairOrder::get_area_total(NULL, $start, $end);
        if ($r !== null) {
            $data = [];
            foreach ($r as $index => $one) {
                $data[] = ['name' => isset($areas[$index]) ? $areas[$index] : '未定义', 'y' => (int) $one];
            }
            $series['area'][] = ['type' => 'column', 'name' => '未设置', 'data' => $data];
        }

        //类型合计
        $repair_type = RepairOrder::get_type_total('', $start, $end);
        arsort($repair_type);
        $data = [];
        foreach ($repair_type as $index => $one) {
            $data[] = ['name' => isset($types[$index]) ? $types[$index] : '未定义', 'y' => (int) $one];
        }
        $series['type_total'][] = ['type' => 'pie', 'name' => '合计', 'data' => $data];

        //类型区域
        foreach ($areas as $key => $area) {
            $r = RepairOrder::get_type_total($key, $start, $end);
            $data = [];
            foreach ($r as $index => $one) {
                $data[] = ['name' => isset($types[$index]) ? $types[$index] : '未定义', 'y' => (int) $one];
            }
            $series['type'][] = ['type' => 'column', 'name' => $area, 'data' => $data];
        }
        //未设置区域
        $r = RepairOrder::get_type_total(NULL, $start, $end);
        if ($r !== null) {
            $data = [];
            foreach ($r as $index => $one) {
                $data[] = ['name' => isset($types[$index]) ? $types[$index] : '未定义', 'y' => (int) $one];
            }
            $series['type'][] = ['type' => 'column', 'name' => '未设置', 'data' => $data];
        }
        //评价
        $evaluate1 = RepairOrder::get_evaluate_total('evaluate1', $start, $end);
        arsort($evaluate1);
        $data = [];
        foreach ($evaluate1 as $index => $one) {
            $data[] = ['name' => RepairOrder::$List['evaluate'][$index], 'y' => (int) $one];
        }
        $series['evaluate1'][] = ['type' => 'pie', 'name' => '合计', 'data' => $data];

        $evaluate2 = RepairOrder::get_evaluate_total('evaluate2', $start, $end);
        arsort($evaluate2);
        $data = [];
        foreach ($evaluate2 as $index => $one) {
            $data[] = ['name' => RepairOrder::$List['evaluate'][$index], 'y' => (int) $one];
        }
        $series['evaluate2'][] = ['type' => 'pie', 'name' => '合计', 'data' => $data];

        $evaluate3 = RepairOrder::get_evaluate_total('evaluate3', $start, $end);
        arsort($evaluate3);
        $data = [];
        foreach ($evaluate3 as $index => $one) {
            $data[] = ['name' => RepairOrder::$List['evaluate'][$index], 'y' => (int) $one];
        }
        $series['evaluate3'][] = ['type' => 'pie', 'name' => '合计', 'data' => $data];

        //满意度趋势
        $day_evaluate1 = RepairOrder::get_evaluate_avg('evaluate1', $start, $end);
        $day_evaluate2 = RepairOrder::get_evaluate_avg('evaluate2', $start, $end);
        $day_evaluate3 = RepairOrder::get_evaluate_avg('evaluate3', $start, $end);
        $data = [];
        $data1 = [];
        $data2 = [];
        for ($i = $start; $i < $end; $i = $i + 86400) {
            $j = date('Y-m-d', $i);
            $data[] = ['name' => $j, 'y' => isset($day_evaluate1[$j]) ? (float) $day_evaluate1[$j] : null];
            $data1[] = ['name' => $j, 'y' => isset($day_evaluate2[$j]) ? (float) $day_evaluate2[$j] : null];
            $data2[] = ['name' => $j, 'y' => isset($day_evaluate3[$j]) ? (float) $day_evaluate3[$j] : null];
        }
        $series['day_evaluat'][] = ['type' => 'line', 'name' => $model->getAttributeLabel('evaluate1'), 'data' => $data];
        $series['day_evaluat'][] = ['type' => 'line', 'name' => $model->getAttributeLabel('evaluate2'), 'data' => $data1];
        $series['day_evaluat'][] = ['type' => 'line', 'name' => $model->getAttributeLabel('evaluate3'), 'data' => $data2];


        //趋势
        $day_created = RepairOrder::get_day_total('created_at', $start, $end);
        $day_accept = RepairOrder::get_day_total('accept_at', $start, $end);
        $day_repair = RepairOrder::get_day_total('repair_at', $start, $end);
        $data = [];
        $data1 = [];
        $data2 = [];
        for ($i = $start; $i < $end; $i = $i + 86400) {
            $j = date('Y-m-d', $i);
            $data[] = ['name' => $j, 'y' => isset($day_created[$j]) ? (int) $day_created[$j] : 0];
            $data1[] = ['name' => $j, 'y' => isset($day_accept[$j]) ? (int) $day_accept[$j] : 0];
            $data2[] = ['name' => $j, 'y' => isset($day_repair[$j]) ? (int) $day_repair[$j] : 0];
        }
        $series['day'][] = ['type' => 'line', 'name' => '报修数', 'data' => $data];
        $series['day'][] = ['type' => 'line', 'name' => '受理数', 'data' => $data1];
        $series['day'][] = ['type' => 'line', 'name' => '维修数', 'data' => $data2];

        //人员
        $work_accept = RepairOrder::get_work_total('accept_at', 'accept_uid', $start, $end);
        $work_dispatch = RepairOrder::get_work_total('dispatch_at', 'dispatch_uid', $start, $end);
        $work_repair = RepairOrder::get_work_total('repair_at', 'worker_id', $start, $end);
        $data = [];
        $keys = array_unique(array_merge(array_keys($work_accept), array_keys($work_dispatch)));
        $work = [];
        foreach ($keys as $one) {
            $num = RepairWorker::find()->where(['uid' => $one])->count();
            if ($num == 1) {
                $worker = RepairWorker::find()->where(['uid' => $one])->one();
                $work[$worker->id] = ['name' => $worker->name, 'accept' => $work_accept[$one], 'dispatch' => $work_dispatch[$one]];
            } else {
                $user = \common\models\User::find()->where(['id' => $one])->one();
                $work['u' . $user->id] = ['name' => $user->username, 'accept' => $work_accept[$one], 'dispatch' => $work_dispatch[$one]];
            }
        }
        foreach ($work_repair as $k => $one) {
            if (array_key_exists($k, $work)) {
                $work[$k]['repair'] = $one;
            } else {
                $worker = RepairWorker::find()->where(['id' => $k])->one();
                $work[$worker->id] = ['name' => $worker->name, 'repair' => $one];
            }
        }

        ksort($work);
        $accept = [];
        $dispatch = [];
        $repair = [];
        foreach ($work as $w) {
            $accept[] = ['name' => $w['name'], 'y' => isset($w['accept']) ? (int) $w['accept'] : null];
            $dispatch[] = ['name' => $w['name'], 'y' => isset($w['dispatch']) ? (int) $w['dispatch'] : null];
            if (isset($w['repair'])) {
                $repair[] = ['name' => $w['name'], 'y' => (int) $w['repair']];
            }
        }
        $series['work'][] = ['type' => 'column', 'name' => '受理数', 'data' => $accept];
        $series['work'][] = ['type' => 'column', 'name' => '派工数', 'data' => $dispatch];
        $series['work'][] = ['type' => 'column', 'name' => '维修数', 'data' => $repair];

        $series['work_repair'][] = ['type' => 'pie', 'name' => '维修数', 'data' => $repair];


        return $this->render('repair', ['model' => $model, 'series' => $series, 'start' => $start, 'end' => $end]);
    }

    public function actionPickup() {

        $start = strtotime('-1 month +1 days');
        $end = strtotime('today') + 86399;

        if (Yii::$app->request->get('range')) {
            $range = explode('至', Yii::$app->request->get('range'));
            $start = isset($range[0]) ? strtotime($range[0]) : $start;
            $end = isset($range[1]) && (strtotime($range[1]) < $end) ? strtotime($range[1]) + 86399 : $end;
        }


        $model = new Pickup();

        $series = [];

        //趋势
        $day_pick = Pickup::find()->where(['type' => Pickup::TYPE_PICK])->andFilterWhere(['>=', 'created_at', $start])->andFilterWhere(['<=', 'created_at', $end])->groupBy(["FROM_UNIXTIME(created_at, '%Y-%m-%d')"])->select(['count(*)'])->indexBy("FROM_UNIXTIME(created_at, '%Y-%m-%d')")->column();
        $day_lose = Pickup::find()->where(['type' => Pickup::TYPE_LOSE])->andFilterWhere(['>=', 'created_at', $start])->andFilterWhere(['<=', 'created_at', $end])->groupBy(["FROM_UNIXTIME(created_at, '%Y-%m-%d')"])->select(['count(*)'])->indexBy("FROM_UNIXTIME(created_at, '%Y-%m-%d')")->column();

        $data1 = [];
        $data2 = [];
        for ($i = $start; $i < $end; $i = $i + 86400) {
            $j = date('Y-m-d', $i);
            $data1[] = ['name' => $j, 'y' => isset($day_pick[$j]) ? (int) $day_pick[$j] : 0];
            $data2[] = ['name' => $j, 'y' => isset($day_lose[$j]) ? (int) $day_lose[$j] : 0];
        }

        $series['day'][] = ['type' => 'line', 'name' => '招领数', 'data' => $data1];
        $series['day'][] = ['type' => 'line', 'name' => '寻物数', 'data' => $data2];

        //类型合计
        $pickup_type = Pickup::get_type_total('', $start, $end);
        arsort($pickup_type);
        $data = [];
        foreach ($pickup_type as $index => $one) {
            $data[] = ['name' => isset(Pickup::$List['type'][$index]) ? Pickup::$List['type'][$index] : '未定义', 'y' => (int) $one];
        }

        $series['type_total'][] = ['type' => 'pie', 'name' => '合计', 'data' => $data];

        //类型状态
        foreach (Pickup::$List['stat'] as $key => $stat) {
            $r = Pickup::get_type_total($key, $start, $end);
            $data = [];
            foreach ($r as $index => $one) {
                $data[] = ['name' => isset(Pickup::$List['type'][$index]) ? Pickup::$List['type'][$index] : '未定义', 'y' => (int) $one];
            }
            $series['type'][] = ['type' => 'column', 'name' => $stat, 'data' => $data];
        }

        return $this->render('pickup', ['model' => $model, 'series' => $series, 'start' => $start, 'end' => $end]);
    }

}
