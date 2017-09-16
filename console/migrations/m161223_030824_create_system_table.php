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
            [1, NULL, 'system', '系统信息', 'group', '', '', '', 1],
            [2, NULL, 'smtp', '邮件设置', 'group', '', '', '', 2],
            [3, NULL, 'captcha', '验证码设置', 'group', '', '', '', 3],
            [4, NULL, 'business', '业务设置', 'group', '', '', '', 4],
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
            [401, 4, 'business_forum', '楼苑设置', 'radio', '{"1":"楼群","2":"独栋"}', '', '1', 1],
            [402, 4, 'business_room', '房间设置', 'radio', '{"1":"一级","2":"二级"}', '', '2', 2],
            [403, 4, 'business_roomtype', '房型设置', 'radio', '{"1":"套间","2":"单间"}', '', '1', 3],
            [404, 4, 'business_bed', '床位设置', 'radio', '{"1":"大室","2":"小室"}', '', '1', 4],
            [405, 4, 'business_repair', '报修设置', 'radio', '{"1":"类型区域必填","2":"类型区域可为空"}', '', '1', 5],
            [406, 4, 'repair_wechat_send', '微信消息', 'checkbox', '{"1":"创建","2":"受理","3":"派工","4":"维修","5":"评价"}', '', '', 6],
            [407, 4, 'business_accept', '报修受理', 'radio', '{"1":"派工","2":"受理"}', '', '1', 7],
            [408, 4, 'business_dispatch', '派工设置', 'radio', '{"1":"全部","2":"匹配"}', '', '2', 8],
            [409, 4, 'business_repairupdate', '报修修改', 'radio', '{"1":"允许","2":"不允许"}', '', '1', 9],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%system}}');
    }

}
