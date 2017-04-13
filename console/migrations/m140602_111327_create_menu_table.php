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
            [1, '系统设置', NULL, '/system/index', 2, '{"icon":"fa fa-cogs"}'],
            [2, '系统信息', 1, '/system/index', 1, '{"icon":"fa fa-cog"}'],
            [3, '邮件设置', 1, '/system/smtp', 2, '{"icon":"fa fa-envelope-o"}'],
            [4, '验证码设置', 1, '/system/captcha', 3, '{"icon":"fa fa-key"}'],
            [5, '账号信息', NULL, '/account/index', 1, '{"icon":"fa fa-list-alt"}'],
            [6, '注册信息', 5, '/account/index', 1, '{"icon":"fa fa-pencil-square-o"}'],
            [7, '修改密码', 5, '/account/change-password', 2, '{"icon":"fa fa-unlock-alt"}'],
            [8, '头像设置', 5, '/account/thumb', 3, '{"icon":"fa fa-camera-retro"}'],
            [9, '参数设置', NULL, '/common/college', 3, '{"icon":"fa fa-tasks"}'],
            [10, '学院设置', 9, '/common/college', 1, '{"icon":"fa fa-university","multi-action":["college","college-create","college-update"]}'],
            [11, '专业设置', 9, '/common/major', 2, '{"icon":"fa fa-graduation-cap", "multi-action":[ "major", "major-create", "major-update"]}'],
            [12, '年级设置', 9, '/common/grade', 3, '{"icon":"fa fa-bars", "multi-action":[ "grade", "grade-create", "grade-update"]}'],
            [13, '楼苑设置', NULL, '/forum/forum', 4, '{"icon":"fa fa-cubes"}'],
            [14, '楼层设置', 13, '/forum/floor', 1, '{"icon":"fa fa-tasks", "multi-action":[ "floor", "floor-create", "floor-update"]}'],
            [15, '楼苑管理', 13, '/forum/forum', 2, '{"icon":"fa fa-building-o", "multi-action":[ "forum", "forum-create", "forum-update"]}'],
            [16, '房间管理', 13, '/forum/room', 3, '{"icon":"fa fa-calendar", "multi-action":[ "room", "room-create", "room-update"]}'],
        ]);
        //$this->insert($menuTable, ['id' => 1, 'name' => '系统设置', 'parent' => NULL, 'route' => '/system/index', 'order' => 2, 'data' => '{"icon":"fa fa-cogs"}']);
        //$this->insert($menuTable, ['id' => 2, 'name' => '系统信息', 'parent' => 1, 'route' => '/system/index', 'order' => 1, 'data' => '{"icon":"fa fa-cog"}']);
        //$this->insert($menuTable, ['id' => 3, 'name' => '邮件设置', 'parent' => 1, 'route' => '/system/smtp', 'order' => 2, 'data' => '{"icon":"fa fa-envelope-o"}']);
        //$this->insert($menuTable, ['id' => 4, 'name' => '验证码设置', 'parent' => 1, 'route' => '/system/captcha', 'order' => 3, 'data' => '{"icon":"fa fa-key"}']);
        //$this->insert($menuTable, ['id' => 5, 'name' => '账号信息', 'parent' => NULL, 'route' => '/account/index', 'order' => 1, 'data' => '{"icon":"fa fa-list-alt"}']);
        //$this->insert($menuTable, ['id' => 6, 'name' => '注册信息', 'parent' => 5, 'route' => '/account/index', 'order' => 1, 'data' => '{"icon":"fa fa-pencil-square-o"}']);
        //$this->insert($menuTable, ['id' => 7, 'name' => '修改密码', 'parent' => 5, 'route' => '/account/change-password', 'order' => 2, 'data' => '{"icon":"fa fa-unlock-alt"}']);
        //$this->insert($menuTable, ['id' => 8, 'name' => '头像设置', 'parent' => 5, 'route' => '/account/thumb', 'order' => 3, 'data' => '{"icon":"fa fa-camera-retro"}']);
        //$this->insert($menuTable, [' id' => 9, 'name' => '参数设置', 'parent' => NULL, 'route' => '/common/college', 'order' => 3, 'data' => '{"icon":"fa fa-tasks"}']);
        //$this->insert($menuTable, ['id' => 10, 'name' => '学院设置', 'parent' => 9, 'route' => '/common/college', 'order' => 1, 'data' => '{"icon":"fa fa-university", "multi-action":[ "college", "college-create", "college-update"]}']);
        //$this->insert($menuTable, ['id' => 11, 'name' => '专业设置', 'parent' => 9, 'route' => '/common/major', 'order' => 2, 'data' => '{"icon":"fa fa-graduation-cap", "multi-action":[ "major", "major-create", "major-update"]}']);
        //$this->insert($menuTable, ['id' => 12, 'name' => '年级设置', 'parent' => 9, 'route' => '/common/grade', 'order' => 3, 'data' => '{"icon":"fa fa-bars", "multi-action":[ "grade", "grade-create", "grade-update"]}']);
        //$this->insert($menuTable, ['id' => 13, 'name' => '楼苑设置', 'parent' => NULL, 'route' => '/forum/forum', 'order' => 4, 'data' => '{"icon":"fa fa-cubes"}']);
        //$this->insert($menuTable, ['id' => 14, 'name' => '楼层设置', 'parent' => 13, 'route' => '/forum/floor', 'order' => 1, 'data' => '{"icon":"fa fa-tasks", "multi-action":[ "forum", "floor-create", "floor-update"]}']);
        //$this->insert($menuTable, ['id' => 15, 'name' => '楼苑管理', 'parent' => 13, 'route' => '/forum/forum', 'order' => 2, 'data' => '{"icon":"fa fa-building-o", "multi-action":[ "forum", "forum-create", "forum-update"]}']);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable(Configs::instance()->menuTable);
    }

}
