<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

class m170917_102541_create_suggest extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%suggest}}';
        $userTable = Configs::instance()->userTable;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="投诉建议表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'serial' => $this->string(16)->notNull(),
            'uid' => $this->integer(),
            'name' => $this->string(16),
            'tel' => $this->string(64),
            'title' => $this->string()->notNull(),
            'content' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'reply_at' => $this->integer(),
            'reply_uid' => $this->integer(),
            'reply_content' => $this->text(),
            'evaluate1' => $this->integer(),
            'evaluate' => $this->smallInteger(),
            'note' => $this->string(),
            'end_at' => $this->integer()->notNull(),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[reply_uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%suggest}}');
    }

}
