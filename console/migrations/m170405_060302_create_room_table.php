<?php

use yii\db\Migration;

/**
 * Handles the creation of table `broom`.
 */
class m170405_060302_create_room_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%room}}';
        $forumTable = '{{%forum}}';
        $floorTable = '{{%parameter}}';
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="房间表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'fid' => $this->integer(),
            'floor' => $this->integer(),
            'rid' => $this->integer(),
            'name' => $this->string(32)->notNull(),
            'note' => $this->string(64),
            'fname' => $this->string(32)->notNull(),
            'gender' => $this->string(6),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[fid]]) REFERENCES {$forumTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
            "FOREIGN KEY ([[floor]]) REFERENCES {$floorTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[rid]]) REFERENCES {$table}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);
//        $this->createIndex('name', $table, ['name', 'fid', 'floor', 'rid'], true);
        //测试数据
        $this->batchInsert($table, ['id', 'fid', 'floor', 'rid', 'name', 'note', 'fname', 'gender', 'stat'], [
            ['1', '4', '5', null, '101', '', '101', '', '1'],
            ['2', '4', '5', null, '102', '', '102', '', '1'],
            ['3', '4', '5', null, '103', '', '103', '', '1'],
            ['4', '4', '6', null, '201', '', '201', '', '1'],
            ['5', '4', '6', null, '202', '', '202', '', '1'],
            ['6', '4', '6', null, '203', '', '203', '', '1'],
            ['7', '7', '5', null, '101', '', '101', '', '1'],
            ['8', '7', '5', null, '102', '', '102', '', '1'],
            ['9', '7', '5', null, '103', '', '103', '', '1'],
            ['10', '4', '5', '1', '1', '', '101', '', '1'],
            ['11', '4', '5', '1', '2', '', '101', '', '1'],
            ['12', '4', '5', '2', '1', '', '102', '', '1'],
            ['13', '4', '5', '2', '2', '', '102', '', '1'],
            ['14', '4', '5', '3', '1', '', '103', '', '1'],
            ['15', '4', '5', '3', '2', '', '103', '', '1'],
            ['16', '4', '6', '4', '1', '', '201', '', '1'],
            ['17', '4', '6', '4', '2', '', '201', '', '1'],
            ['18', '4', '6', '5', '1', '', '202', '', '1'],
            ['19', '4', '6', '5', '2', '', '202', '', '1'],
            ['20', '4', '6', '6', '1', '', '203', '', '1'],
            ['21', '4', '6', '6', '2', '', '203', '', '1'],
            ['22', '7', '5', '7', '1', '', '101', '', '1'],
            ['23', '7', '5', '7', '2', '', '101', '', '1'],
            ['24', '7', '5', '8', '1', '', '102', '', '1'],
            ['25', '7', '5', '8', '2', '', '102', '', '1'],
            ['26', '7', '5', '9', '1', '', '103', '', '1'],
            ['27', '7', '5', '9', '2', '', '103', '', '1'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%room}}');
    }

}
