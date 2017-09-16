<?php

namespace dms\components;

use Yii;
use yii\web\Controller;
use common\models\UserAuth;

class Helper extends Controller {

    public function sendWechatTemplate($uid, $template, $model) {

        $wechat = Yii::$app->wechat;
        $tousers = UserAuth::getTouser($uid);
        $result = true;
        if ($template == 'repaire_create') {
            $data = [
                'template_id' => 'px-_23ZPiLj9PSKO-Vz2Vn2heXw11djEzZACxxVNjJg',
                'url' => 'http://ny.gxgygl.com/wechat/redirect?url=http://ny.gxgygl.com/work/repair-work',
                'data' => [
                    'first' => ['value' => '您好，您有新的报修单',],
                    'serial' => ['value' => $model->serial,],
                    'stat' => ['value' => $model->Stat,],
                    'created_at' => ['value' => date('Y-m-d H:i:s', $model->created_at),],
                    'user' => ['value' => $model->name,],
                    'address' => ['value' => $model->address,],
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
