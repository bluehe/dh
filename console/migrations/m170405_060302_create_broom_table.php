<?php

use yii\db\Migration;

/**
 * Handles the creation of table `broom`.
 */
class m170405_060302_create_broom_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%broom}}';
        $forumTable = '{{%forum}}';
        $floorTable = '{{%parameter}}';
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="大室表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'fid' => $this->integer(),
            'floor' => $this->integer(),
            'name' => $this->string(32)->notNull(),
            'note' => $this->string(64),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[fid]]) REFERENCES {$forumTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
            "FOREIGN KEY ([[floor]]) REFERENCES {$floorTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%broom}}');
    }

}
