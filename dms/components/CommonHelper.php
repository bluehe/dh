<?php

namespace dms\components;

use Yii;
use common\models\UserAuth;

class CommonHelper {

    public function sendWechatTemplate($uid, $template, $model) {

        $wechat = Yii::$app->wechat;
        $tousers = UserAuth::getTouser($uid);
        $result = true;
        if ($template == 'repaire_create') {
            $data = [
                'template_id' => 'px-_23ZPiLj9PSKO-Vz2Vn2heXw11djEzZACxxVNjJg',
                'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/work/repair-work?RepairOrderSearch[stat]=' . \dms\models\RepairOrder::STAT_OPEN,
                'data' => [
                    'first' => ['value' => '您好，您有新的报修单',],
                    'serial' => ['value' => $model->serial,],
                    'stat' => ['value' => $model->Stat,],
                    'created_at' => ['value' => date('Y-m-d H:i:s', $model->created_at),],
                    'user' => ['value' => $model->name,],
                    'address' => ['value' => ($model->repair_area ? \dms\models\Forum::get_forum_allname($model->repair_area) : '') . '-' . $model->address],
                    'type' => ['value' => $model->repair_type ? $model->type->v : $model->repair_type],
                    'content' => ['value' => $model->content,],
                    'remark' => ['value' => '点击查看详情！',],
            ]];

            foreach ($tousers as $touser) {
                $data['touser'] = $touser;
                $wechat->sendTemplateMessage($data);
            }
        } else if ($template == 'repaire_accept') {
            $data = [
                'template_id' => 'px-_23ZPiLj9PSKO-Vz2Vn2heXw11djEzZACxxVNjJg',
                'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business',
                'data' => [
                    'first' => ['value' => '您好，您的报修单有新的状态',],
                    'serial' => ['value' => $model->serial,],
                    'stat' => ['value' => $model->Stat,],
                    'created_at' => ['value' => date('Y-m-d H:i:s', $model->created_at),],
                    'user' => ['value' => $model->name,],
                    'address' => ['value' => ($model->repair_area ? \dms\models\Forum::get_forum_allname($model->repair_area) : '') . '-' . $model->address,],
                    'type' => ['value' => $model->repair_type ? $model->type->v : $model->repair_type],
                    'content' => ['value' => $model->content,],
                    'remark' => ['value' => '点击查看详情！',],
            ]];

            foreach ($tousers as $touser) {
                $data['touser'] = $touser;
                $wechat->sendTemplateMessage($data);
            }
        } else if ($template == 'repaire_dispatch') {
            $data = [
                'template_id' => 'px-_23ZPiLj9PSKO-Vz2Vn2heXw11djEzZACxxVNjJg',
                'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business',
                'data' => [
                    'first' => ['value' => '您好，您的报修单已经派单',],
                    'serial' => ['value' => $model->serial,],
                    'stat' => ['value' => $model->Stat,],
                    'created_at' => ['value' => date('Y-m-d H:i:s', $model->created_at),],
                    'user' => ['value' => $model->name,],
                    'address' => ['value' => ($model->repair_area ? \dms\models\Forum::get_forum_allname($model->repair_area) : '') . '-' . $model->address,],
                    'type' => ['value' => $model->repair_type ? $model->type->v : $model->repair_type],
                    'content' => ['value' => $model->content,],
                    'remark' => ['value' => '点击查看详情！',],
            ]];

            foreach ($tousers as $touser) {
                $data['touser'] = $touser;
                $wechat->sendTemplateMessage($data);
            }
        } else if ($template == 'repaire_dispatch_worker') {
            $data = [
                'template_id' => 'px-_23ZPiLj9PSKO-Vz2Vn2heXw11djEzZACxxVNjJg',
                'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/work/repair-work?RepairOrderSearch[stat]=' . \dms\models\RepairOrder::STAT_DISPATCH,
                'data' => [
                    'first' => ['value' => '您好，您有新的维修任务',],
                    'serial' => ['value' => $model->serial,],
                    'stat' => ['value' => $model->Stat,],
                    'created_at' => ['value' => date('Y-m-d H:i:s', $model->created_at),],
                    'user' => ['value' => $model->name,],
                    'address' => ['value' => ($model->repair_area ? \dms\models\Forum::get_forum_allname($model->repair_area) : '') . '-' . $model->address,],
                    'type' => ['value' => $model->repair_type ? $model->type->v : $model->repair_type],
                    'content' => ['value' => $model->content,],
                    'remark' => ['value' => '点击查看详情！',],
            ]];

            foreach ($tousers as $touser) {
                $data['touser'] = $touser;
                $wechat->sendTemplateMessage($data);
            }
        } else if ($template == 'repaire_repair') {
            $data = [
                'template_id' => 'px-_23ZPiLj9PSKO-Vz2Vn2heXw11djEzZACxxVNjJg',
                'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/business/repair-business',
                'data' => [
                    'first' => ['value' => '您好，您的报修单已经完工，请评价',],
                    'serial' => ['value' => $model->serial,],
                    'stat' => ['value' => $model->Stat,],
                    'created_at' => ['value' => date('Y-m-d H:i:s', $model->created_at),],
                    'user' => ['value' => $model->name,],
                    'address' => ['value' => ($model->repair_area ? \dms\models\Forum::get_forum_allname($model->repair_area) : '') . '-' . $model->address,],
                    'type' => ['value' => $model->repair_type ? $model->type->v : $model->repair_type],
                    'content' => ['value' => $model->content,],
                    'remark' => ['value' => '点击查看详情！',],
            ]];

            foreach ($tousers as $touser) {
                $data['touser'] = $touser;
                $wechat->sendTemplateMessage($data);
            }
        } else if ($template == 'repaire_evaluate') {
            $data = [
                'template_id' => 'px-_23ZPiLj9PSKO-Vz2Vn2heXw11djEzZACxxVNjJg',
                'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/work/repair-work?RepairOrderSearch[stat]=' . \dms\models\RepairOrder::STAT_EVALUATE,
                'data' => [
                    'first' => ['value' => '您好，您收到新的评价',],
                    'serial' => ['value' => $model->serial,],
                    'stat' => ['value' => $model->Stat,],
                    'created_at' => ['value' => date('Y-m-d H:i:s', $model->created_at),],
                    'user' => ['value' => $model->name,],
                    'address' => ['value' => ($model->repair_area ? \dms\models\Forum::get_forum_allname($model->repair_area) : '') . '-' . $model->address,],
                    'type' => ['value' => $model->repair_type ? $model->type->v : $model->repair_type],
                    'content' => ['value' => $model->content,],
                    'remark' => ['value' => '点击查看详情！',],
            ]];

            foreach ($tousers as $touser) {
                $data['touser'] = $touser;
                $wechat->sendTemplateMessage($data);
            }
        }
        return $result;
    }

}
