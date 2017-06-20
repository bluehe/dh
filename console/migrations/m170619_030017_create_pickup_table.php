<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

/**
 * Handles the creation of table `pickup`.
 */
class m170619_030017_create_pickup_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%pickup}}';
        $userTable = Configs::instance()->userTable;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="拾物登记表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'uid' => $this->integer(),
            'name' => $this->string(16)->notNull(),
            'tel' => $this->string(64)->notNull(),
            'goods' => $this->string()->notNull(),
            'address' => $this->string(),
            'content' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'end_at' => $this->integer(),
            'end_uid' => $this->integer(),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[end_uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%pickup}}');
    }

}
