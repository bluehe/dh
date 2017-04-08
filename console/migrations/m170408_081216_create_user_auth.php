<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

class m170408_081216_create_user_auth extends Migration {

    public function up() {
        $table = '{{%user_auth}}';
        $userTable = Configs::instance()->userTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="第三方登录表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'uid' => $this->integer()->notNull(),
            'type' => $this->string(10)->notNull(),
            'open_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer()->notNull(),
            "FOREIGN KEY ([[uid]]) REFERENCES {$userTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{%user_auth}}');
    }

}
