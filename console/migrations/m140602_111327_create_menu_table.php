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
            [5, '系统设置', NULL, '/system/index', 4, '{"icon":"fa fa-cogs"}'],
            [6, '系统信息', 5, '/system/index', 1, '{"icon":"fa fa-cog"}'],
            [7, '邮件设置', 5, '/system/smtp', 2, '{"icon":"fa fa-envelope-o"}'],
            [8, '验证码设置', 5, '/system/captcha', 3, '{"icon":"fa fa-key"}'],
            [9, '业务设置', 5, '/system/business', 4, '{"icon":"fa fa-sitemap"}'],
            [10, '学院设置', NULL, '/college/college', 5, '{"icon":"fa fa-tasks"}'],
            [11, '学院管理', 10, '/college/college', 1, '{"icon":"fa fa-university","multi-action":["college","college-create","college-update"]}'],
            [12, '专业管理', 10, '/college/major', 2, '{"icon":"fa fa-graduation-cap", "multi-action":[ "major", "major-create", "major-update"]}'],
            [13, '年级管理', 10, '/college/grade', 3, '{"icon":"fa fa-bars", "multi-action":[ "grade", "grade-create", "grade-update"]}'],
            [14, '楼苑设置', NULL, '/forum/forum', 6, '{"icon":"fa fa-cubes"}'],
            [15, '楼层管理', 14, '/forum/floor', 1, '{"icon":"fa fa-tasks", "multi-action":[ "floor", "floor-create", "floor-update"]}'],
            [16, '楼苑管理', 14, '/forum/forum', 2, '{"icon":"fa fa-building-o", "multi-action":[ "forum", "forum-create", "forum-update"]}'],
            [17, '房间管理', 14, '/forum/room', 3, '{"icon":"fa fa-home", "multi-action":[ "room", "room-create", "room-update"]}'],
            [18, '床位管理', 14, '/forum/bed', 4, '{"icon":"fa fa-bed", "multi-action":[ "bed", "bed-create", "bed-update"]}'],
            [19, '维修设置', NULL, '/repair/unit', 7, '{"icon":"fa fa-wrench"}'],
            [20, '类型管理', 19, '/repair/type', 1, '{"icon":"fa fa-tasks", "multi-action":[ "type", "type-create", "type-update"]}'],
            [21, '单位管理', 19, '/repair/unit', 2, '{"icon":"fa fa-newspaper-o", "multi-action":[ "unit", "unit-create", "unit-update"]}'],
            [22, '人员管理', 19, '/repair/worker', 3, '{"icon":"fa fa-user", "multi-action":[ "worker", "worker-create", "worker-update"]}'],
            [23, '业务中心', NULL, '/business/repair-business', 2, '{"icon":"fa  fa-briefcase"}'],
            [24, '网上报修', 23, '/business/repair-business', 1, '{"icon":"fa fa-wrench","multi-action":[ "repair-business", "repair-create","repair-update"]}'],
            [25, '日常事务', NULL, '/work/repair-work', 3, '{"icon":"fa  fa-briefcase"}'],
            [26, '报修管理', 25, '/work/repair-work', 1, '{"icon":"fa fa-wrench"}'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable(Configs::instance()->menuTable);
    }

}
