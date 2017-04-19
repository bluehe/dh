<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

class m170417_065121_create_repair_worker extends Migration {

    public function up() {
        $table = '{{%repair_worker}}';
        $unitTable = '{{%repair_unit}}';
        $userTable = Configs::instance()->userTable;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="维修单位表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'uid' => $this->integer(),
            'unit_id' => $this->integer(),
            'name' => $this->string(8)->notNull(),
            'tel' => $this->string(64)->notNull(),
            'email' => $this->string(64),
            'address' => $this->string(),
            'note' => $this->string(),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[unit_id]]) REFERENCES {$unitTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{%repair_worker}}');
    }

}
