<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

/**
 * Handles the creation of table `task`.
 */
class m170509_134621_create_task_table extends Migration {
    /**
     * @inheritdoc
     */

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%task}}';
        $userTable = Configs::instance()->userTable;


        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="任务表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'uid' => $this->integer(),
            'relation_uid' => $this->string(),
            'title' => $this->string()->notNull(),
            'content' => $this->string(),
            'end_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'edit' => $this->smallInteger()->notNull()->defaultValue(1),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%task}}');
    }

}
