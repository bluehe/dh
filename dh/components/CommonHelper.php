<?php

namespace dh\components;

use Yii;
use dh\models\UserPoint;
use dh\models\User;

class CommonHelper {

    public static function set_point($uid, $num, $direct = UserPoint::DIRECT_PLUS, $content = '', $note = '') {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $point = new UserPoint();
            $point->uid = $uid;
            $point->direct = $direct;
            $point->num = $num;
            $point->content = $content;
            $point->note = $note;
            $point->created_at = time();
            $point->save();
            User::updateAllCounters(['point' => ($direct == UserPoint::DIRECT_PLUS ? $num : -$num)], ['id' => $uid]);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

}
