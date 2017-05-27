<?php

namespace dms\controllers;

use Yii;
use yii\web\Controller;
use dms\models\RepairOrder;
use dms\models\RepairWorker;
use dms\models\Room;

class StatisticsController extends Controller {

    public function actionRepair() {

        $start = strtotime('-29 days');
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
            $data[] = ['name' => $areas[$index], 'y' => (int) $one];
        }
        $series['area_total'][] = ['type' => 'pie', 'name' => '合计', 'data' => $data];

        //区域类型
        foreach ($types as $key => $type) {
            $r = RepairOrder::get_area_total($key, $start, $end);
            $data = [];
            foreach ($r as $index => $one) {
                $data[] = ['name' => $areas[$index], 'y' => (int) $one];
            }
            $series['area'][] = ['type' => 'column', 'name' => $type, 'data' => $data];
        }

        //类型合计
        $repair_type = RepairOrder::get_type_total('', $start, $end);
        arsort($repair_type);
        $data = [];
        foreach ($repair_type as $index => $one) {
            $data[] = ['name' => $types[$index], 'y' => (int) $one];
        }
        $series['type_total'][] = ['type' => 'pie', 'name' => '合计', 'data' => $data];

        //类型区域
        foreach ($areas as $key => $area) {
            $r = RepairOrder::get_type_total($key, $start, $end);
            $data = [];
            foreach ($r as $index => $one) {
                $data[] = ['name' => $types[$index], 'y' => (int) $one];
            }
            $series['type'][] = ['type' => 'column', 'name' => $area, 'data' => $data];
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

        //趋势
        $day_created = RepairOrder::get_day_total('created_at', $start, $end);
        $day_repair = RepairOrder::get_day_total('repair_at', $start, $end);
        $data = [];
        $data1 = [];
        for ($i = $start; $i < $end; $i = $i + 86400) {
            $j = date('Y-m-d', $i);
            $data[] = ['name' => $j, 'y' => isset($day_created[$j]) ? (int) $day_created[$j] : 0];
            $data1[] = ['name' => $j, 'y' => isset($day_repair[$j]) ? (int) $day_repair[$j] : 0];
        }
        $series['day'][] = ['type' => 'line', 'name' => '报修数', 'data' => $data];
        $series['day'][] = ['type' => 'line', 'name' => '维修数', 'data' => $data1];


        return $this->render('repair', ['model' => $model, 'series' => $series, 'start' => $start, 'end' => $end]);
    }

}
