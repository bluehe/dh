<?php

use yii\db\Migration;

class m170417_083813_create_worker_type extends Migration {

    public function up() {
        $table = '{{%repair_worker_type}}';
        $workerTable = '{{%repair_worker}}';
        $typeTable = '{{%parameter}}';

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="维修人员类型表"';
        }
        $this->createTable($table, [
            'worker' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'PRIMARY KEY (worker, type)',
            "FOREIGN KEY ([[worker]]) REFERENCES {$workerTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
            "FOREIGN KEY ([[type]]) REFERENCES {$typeTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);

        //测试数据
        $this->batchInsert($table, ['worker', 'type'], [['1', '14'], ['2', '14'], ['4', '14'], ['1', '15'], ['2', '15'], ['3', '16']]);
    }

    public function down() {
        $this->dropTable('{{%repair_worker_type}}');
    }

}
