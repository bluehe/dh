<?php

use yii\db\Migration;

/**
 * Handles the creation of table `system`.
 */
class m161223_030824_create_system_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%system}}';
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="系统表"';
        }

        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'code' => $this->string(30)->notNull()->unique(),
            'tag' => $this->string(20)->notNull(),
            'type' => $this->string(10)->notNull(),
            'store_range' => $this->string()->notNull(),
            'store_dir' => $this->string()->notNull(),
            'value' => $this->text()->notNull(),
            'sort_order' => $this->smallInteger(3)->notNull()->defaultValue(1),
            "FOREIGN KEY ([[parent_id]]) REFERENCES {$table}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);
        $this->createIndex('parent_id', $table, 'parent_id');

        //插入数据
        $this->batchInsert($table, ['id', 'parent_id', 'code', 'tag', 'type', 'store_range', 'store_dir', 'value', 'sort_order'], [
            [1, NULL, 'system_info', '系统信息', 'group', '', '', '', 1],
            [2, NULL, 'smtp', '邮件设置', 'group', '', '', '', 2],
            [3, NULL, 'captcha', '验证码设置', 'group', '', '', '', 3],
            [101, 1, 'system_name', '网站名称', 'text', '', '', '', 1],
            [102, 1, 'system_title', '网站标题', 'text', '', '', '', 2],
            [103, 1, 'system_keywords', '关键字', 'textarea', '3', '', '', 3],
            [104, 1, 'system_desc', '网站描述', 'textarea', '3', '', '', 4],
            [105, 1, 'system_icp', '备案信息', 'text', '', '', '', 5],
            [106, 1, 'system_statcode', '第三方统计', 'textarea', '3', '', '', 6],
            [201, 2, 'smtp_service', '自定义邮件', 'radio', '{"0":"否","1":"是"}', '', '0', 1],
            [202, 2, 'smtp_ssl', '加密连接(SSL)', 'radio', '{"0":"否","1":"是"}', '', '0', 2],
            [203, 2, 'smtp_host', 'SMTP服务器', 'text', '', '', '', 3],
            [204, 2, 'smtp_port', 'SMTP端口', 'text', '', '', '', 4],
            [205, 2, 'smtp_from', '发件人地址', 'text', '', '', '', 5],
            [206, 2, 'smtp_username', 'SMTP用户名', 'text', '', '', '', 6],
            [207, 2, 'smtp_password', 'SMTP密码', 'password', '', '', '', 7],
            [208, 2, 'smtp_charset', '邮件编码', 'radio', '{"1":"UTF-8","2":"GB2312"}', '', '1', 8],
            [301, 3, 'captcha_open', '启用验证码', 'checkbox', '{"1":"新用户注册","2":"用户登录","3":"找回密码"}', '', '', 1],
            [302, 3, 'captcha_loginfail', '登录失败显示', 'radio', '{"0":"否","1":"是"}', '', '0', 2],
            [303, 3, 'captcha_length', '验证码长度', 'text', '', '', '6', 3],
        ]);


        //$this->insert($table, ['id' => 1, 'parent_id' => NULL, 'code' => 'system_info', 'tag' => '系统信息', 'type' => 'group', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 1]);
        //$this->insert($table, ['id' => 2, 'parent_id' => NULL, 'code' => 'smtp', 'tag' => '邮件设置', 'type' => 'group', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 2]);
        //$this->insert($table, ['id' => 3, 'parent_id' => NULL, 'code' => 'captcha', 'tag' => '验证码设置', 'type' => 'group', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 3]);
        //$this->insert($table, ['id' => 101, 'parent_id' => 1, 'code' => 'system_name', 'tag' => '网站名称', 'type' => 'text', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 1]);
        //$this->insert($table, ['id' => 102, 'parent_id' => 1, 'code' => 'system_title', 'tag' => '网站标题', 'type' => 'text', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 2]);
        //$this->insert($table, ['id' => 103, 'parent_id' => 1, 'code' => 'system_keywords', 'tag' => '关键字', 'type' => 'textarea', 'store_range' => '3', 'store_dir' => '', 'value' => '', 'sort_order' => 3]);
        //$this->insert($table, ['id' => 104, 'parent_id' => 1, 'code' => 'system_desc', 'tag' => '网站描述', 'type' => 'textarea', 'store_range' => '3', 'store_dir' => '', 'value' => '', 'sort_order' => 4]);
        //$this->insert($table, ['id' => 105, 'parent_id' => 1, 'code' => 'system_icp', 'tag' => '备案信息', 'type' => 'text', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 5]);
        //$this->insert($table, ['id' => 106, 'parent_id' => 1, 'code' => 'system_statcode', 'tag' => '第三方统计', 'type' => 'textarea', 'store_range' => '3', 'store_dir' => '', 'value' => '', 'sort_order' => 6]);
        //$this->insert($table, ['id' => 201, 'parent_id' => 2, 'code' => 'smtp_service', 'tag' => '自定义邮件', 'type' => 'radio', 'store_range' => '{"0":"否","1":"是"}', 'store_dir' => '', 'value' => '0', 'sort_order' => 1]);
        //$this->insert($table, ['id' => 202, 'parent_id' => 2, 'code' => 'smtp_ssl', 'tag' => '加密连接(SSL)', 'type' => 'radio', 'store_range' => '{"0":"否","1":"是"}', 'store_dir' => '', 'value' => '0', 'sort_order' => 2]);
        //$this->insert($table, ['id' => 203, 'parent_id' => 2, 'code' => 'smtp_host', 'tag' => 'SMTP服务器', 'type' => 'text', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 3]);
        //$this->insert($table, ['id' => 204, 'parent_id' => 2, 'code' => 'smtp_port', 'tag' => 'SMTP端口', 'type' => 'text', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 4]);
//        $this->insert($table, ['id' => 205, 'parent_id' => 2, 'code' => 'smtp_from', 'tag' => '发件人地址', 'type' => 'text', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 5]);
//        $this->insert($table, ['id' => 206, 'parent_id' => 2, 'code' => 'smtp_username', 'tag' => 'SMTP用户名', 'type' => 'text', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 6]);
//        $this->insert($table, ['id' => 207, 'parent_id' => 2, 'code' => 'smtp_password', 'tag' => 'SMTP密码', 'type' => 'password', 'store_range' => '', 'store_dir' => '', 'value' => '', 'sort_order' => 7]);
//        $this->insert($table, ['id' => 208, 'parent_id' => 2, 'code' => 'smtp_charset', 'tag' => '邮件编码', 'type' => 'radio', 'store_range' => '{"1":"UTF-8","2":"GB2312"}', 'store_dir' => '', 'value' => '1', 'sort_order' => 8]);
//        $this->insert($table, ['id' => 301, 'parent_id' => 3, 'code' => 'captcha_open', 'tag' => '启用验证码', 'type' => 'checkbox', 'store_range' => '{"1":"新用户注册","2":"用户登录","3":"找回密码"}', 'store_dir' => '', 'value' => '', 'sort_order' => 1]);
//        $this->insert($table, ['id' => 302, 'parent_id' => 3, 'code' => 'captcha_loginfail', 'tag' => '登录失败显示', 'type' => 'radio', 'store_range' => '{"0":"否","1":"是"}', 'store_dir' => '', 'value' => '0', 'sort_order' => 2]);
//        $this->insert($table, ['id' => 303, 'parent_id' => 3, 'code' => 'captcha_length', 'tag' => '验证码长度', 'type' => 'text', 'store_range' => '', 'store_dir' => '', 'value' => '6', 'sort_order' => 3]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%system}}');
    }

}
