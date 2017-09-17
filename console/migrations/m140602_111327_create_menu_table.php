<?php

use mdm\admin\components\Configs;

/**
 * Migration table of table_menu
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class m140602_111327_create_menu_table extends \yii\db\Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $menuTable = Configs::instance()->menuTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($menuTable, [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'parent' => $this->integer(),
            'route' => $this->string(),
            'order' => $this->integer(),
            'data' => $this->binary(),
            "FOREIGN KEY ([[parent]]) REFERENCES {$menuTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);

        //插入数据
        $this->batchInsert($menuTable, ['id', 'name', 'parent', 'route', 'order', 'data'], [
            [1, '账号信息', NULL, '/account/index', 1, '{"icon":"fa fa-list-alt"}'],
            [2, '注册信息', 1, '/account/index', 1, '{"icon":"fa fa-pencil-square-o"}'],
            [3, '修改密码', 1, '/account/change-password', 2, '{"icon":"fa fa-unlock-alt"}'],
            [4, '头像设置', 1, '/account/thumb', 3, '{"icon":"fa fa-camera-retro"}'],
            [20, '业务中心', NULL, '/business/repair-business', 2, '{"icon":"fa  fa-briefcase"}'],
            [21, '网上报修', 20, '/business/repair-business', 1, '{"icon":"fa fa-wrench","multi-action":[ "repair-business", "repair-create","repair-update"]}'],
            [22, '拾物招领', 20, '/business/pickup-business', 2, '{"icon":"fa fa-suitcase","multi-action":[ "pickup-business", "pickup-create", "pickup-update"]}'],
            [23, '投诉建议', 20, '/business/suggest-business', 3, '{"icon":"fa fa-commenting-o","multi-action":[ "suggest-business", "suggest-create", "suggest-update"]}'],
            [30, '日常事务', NULL, '/work/repair-work', 3, '{"icon":"fa  fa-briefcase"}'],
            [31, '报修管理', 30, '/work/repair-work', 1, '{"icon":"fa fa-wrench"}'],
            [32, '拾物管理', 30, '/work/pickup-work', 2, '{"icon":"fa fa-suitcase"}'],
            [33, '投诉管理', 30, '/work/suggest-work', 3, '{"icon":"fa fa-commenting-o"}'],
            [34, '楼苑概况', 30, '/work/building', 4, '{"icon":"fa fa-building-o"}'],
            [35, '教职工管理', 30, '/work/teacher', 5, '{"icon":"fa fa-users","multi-action":[ "teacher", "teacher-create","teacher-update"]}'],
            [36, '学生管理', 30, '/student/student', 6, '{"icon":"fa fa-graduation-cap","multi-action":[ "student", "student-create","student-update"]}'],
            [40, '系统设置', NULL, '/system/index', 4, '{"icon":"fa fa-cogs"}'],
            [41, '系统信息', 40, '/system/index', 1, '{"icon":"fa fa-cog"}'],
            [42, '邮件设置', 40, '/system/smtp', 2, '{"icon":"fa fa-envelope-o"}'],
            [43, '验证码设置', 40, '/system/captcha', 3, '{"icon":"fa fa-key"}'],
            [44, '业务设置', 40, '/system/business', 4, '{"icon":"fa fa-sitemap"}'],
            [45, '计划任务', 40, '/system/crontab', 5, '{"icon":"fa fa-clock-o", "multi-action":[ "crontab", "crontab-create", "crontab-update"]}'],
            [50, '学院设置', NULL, '/college/college', 5, '{"icon":"fa fa-tasks"}'],
            [51, '学院管理', 50, '/college/college', 1, '{"icon":"fa fa-university","multi-action":["college","college-create","college-update"]}'],
            [52, '专业管理', 50, '/college/major', 2, '{"icon":"fa fa-graduation-cap", "multi-action":[ "major", "major-create", "major-update"]}'],
            [53, '年级管理', 50, '/college/grade', 3, '{"icon":"fa fa-bars", "multi-action":[ "grade", "grade-create", "grade-update"]}'],
            [60, '楼苑设置', NULL, '/forum/forum', 6, '{"icon":"fa fa-cubes"}'],
            [61, '楼层管理', 60, '/forum/floor', 1, '{"icon":"fa fa-tasks", "multi-action":[ "floor", "floor-create", "floor-update"]}'],
            [62, '楼苑管理', 60, '/forum/forum', 2, '{"icon":"fa fa-building-o", "multi-action":[ "forum", "forum-create", "forum-update"]}'],
            [63, '房间管理', 60, '/forum/room', 3, '{"icon":"fa fa-home", "multi-action":[ "room", "room-create", "room-update"]}'],
            [64, '床位管理', 60, '/forum/bed', 4, '{"icon":"fa fa-bed", "multi-action":[ "bed", "bed-create", "bed-update"]}'],
            [70, '维修设置', NULL, '/repair/unit', 7, '{"icon":"fa fa-wrench"}'],
            [71, '类型管理', 70, '/repair/type', 1, '{"icon":"fa fa-tasks", "multi-action":[ "type", "type-create", "type-update"]}'],
            [72, '单位管理', 70, '/repair/unit', 2, '{"icon":"fa fa-newspaper-o", "multi-action":[ "unit", "unit-create", "unit-update"]}'],
            [73, '人员管理', 70, '/repair/worker', 3, '{"icon":"fa fa-user", "multi-action":[ "worker", "worker-create", "worker-update"]}'],
            [80, '数据统计', NULL, '/statistics/repair', 8, '{"icon":"fa  fa-bar-chart"}'],
            [81, '报修统计', 80, '/statistics/repair', 1, '{"icon":"fa fa-wrench"}'],
            [82, '拾物统计', 80, '/statistics/pickup', 2, '{"icon":"fa fa-suitcase"}'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable(Configs::instance()->menuTable);
    }

}
