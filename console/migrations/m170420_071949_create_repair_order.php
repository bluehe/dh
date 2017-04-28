<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

class m170420_071949_create_repair_order extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%repair_order}}';
        $userTable = Configs::instance()->userTable;
        $typeTable = '{{%parameter}}';
        $areaTable = '{{%forum}}';
        $workerTable = '{{%repair_worker}}';

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="维修记录表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'serial' => $this->string(16)->notNull(),
            'uid' => $this->integer(),
            'repair_type' => $this->integer(),
            'repair_area' => $this->integer(),
            'address' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => $this->string(),
            'evaluate' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'accept_at' => $this->integer(),
            'accept_uid' => $this->integer(),
            'dispatch_at' => $this->integer(),
            'dispatch_uid' => $this->integer(),
            'repair_at' => $this->integer(),
            'repair_uid' => $this->integer(),
            'worker_id' => $this->integer(),
            'end_at' => $this->integer(),
            'note' => $this->string(),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[accept_uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[dispatch_uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[repair_uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[worker_id]]) REFERENCES {$workerTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[repair_type]]) REFERENCES {$typeTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[repair_area]]) REFERENCES {$areaTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);

        //测试数据
//        $this->batchInsert($table, ['id', 'name', 'company', 'tel', 'email', 'address', 'note', 'stat'], [
//            ['1', '东吴', '东吴物业', '', '', '', '', '1'],
//            ['2', '华盈', '华盈物业', '', '', '', '', '1'],
//        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%repair_order}}');
    }

}
