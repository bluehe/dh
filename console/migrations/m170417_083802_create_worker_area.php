<?php

use yii\db\Migration;

class m170417_083802_create_worker_area extends Migration {

    public function up() {
        $table = '{{%repair_worker_area}}';
        $workerTable = '{{%repair_worker}}';
        $areaTable = '{{%forum}}';

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="维修人员辖区表"';
        }
        $this->createTable($table, [
            'worker' => $this->integer()->notNull(),
            'area' => $this->integer()->notNull(),
            'PRIMARY KEY (worker, area)',
            "FOREIGN KEY ([[worker]]) REFERENCES {$workerTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
            "FOREIGN KEY ([[area]]) REFERENCES {$areaTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);

        //测试数据
        $this->batchInsert($table, ['worker', 'area'], [['3', '4'], ['4', '4'], ['1', '5'], ['2', '5'], ['2', '6'], ['3', '6']]);
    }

    public function down() {
        $this->dropTable('{{%repair_worker_area}}');
    }

}
