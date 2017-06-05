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
            'name' => $this->string(16),
            'tel' => $this->string(64),
            'repair_type' => $this->integer(),
            'repair_area' => $this->integer(),
            'address' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => $this->string(),
            'evaluate1' => $this->integer(),
            'evaluate2' => $this->integer(),
            'evaluate3' => $this->integer(),
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
            'evaluate' => $this->smallInteger(),
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
        $this->batchInsert($table, ['id', 'serial', 'uid', 'name', 'tel', 'repair_type', 'repair_area', 'address', 'title', 'content', 'evaluate1', 'evaluate2', 'evaluate3', 'created_at', 'accept_at', 'accept_uid', 'dispatch_at', 'dispatch_uid', 'repair_at', 'repair_uid', 'worker_id', 'end_at', 'note', 'evaluate', 'stat'], [
            ['1', 'BX170519001', '1', 'test', '13112341234', '14', '4', '12', '', '12', '2', '2', '3', '1495185869', '1495185926', '1', '1495185926', '1', '1495185931', '1', '4', '1495185960', '', '2', '5'],
            ['2', 'BX170519002', '1', 'test', '13112341234', '14', '5', '213', '', '12', '3', '3', '4', '1495185876', '1495185926', '1', '1495185926', '1', '1495185931', '1', '1', '1495185977', '', '2', '5'],
            ['3', 'BX170519003', '1', 'test', '13112341234', '16', '6', '12', '', '12', '1', '5', '2', '1495185885', '1495185926', '1', '1495185926', '1', '1495185931', '1', '3', '1495185954', '', '2', '5'],
            ['4', 'BX170519004', '1', 'test', '13112341234', '15', '6', '12', '', '121', '4', '2', '3', '1495185893', '1495185926', '1', '1495185926', '1', '1495185930', '1', '2', '1495185965', '', '2', '5'],
            ['5', 'BX170519005', '1', 'test', '13112341234', '16', '7', '12', '', '122', '4', '4', '2', '1495185902', '1495185926', '1', '1495185926', '1', '1495185930', '1', '3', '1495185971', '', '2', '5'],
            ['6', 'BX170519006', '1', 'test', '13112341234', '14', '5', '122', '', '122', '5', '5', '5', '1495185909', '1495185926', '1', '1495185926', '1', '1495185930', '1', '2', '1495185950', '', '2', '5'],
            ['7', 'BX170519007', '1', 'test', '13112341234', '14', '4', '122', '', '122', '4', '5', '4', '1495185917', '1495185926', '1', '1495185926', '1', '1495185930', '1', '4', '1495185946', '', '2', '5'],
            ['8', 'BX170519008', '1', 'test', '13112341234', '14', '5', '212', '', '12', '5', '5', '5', '1495186059', '1495186111', '1', '1495186111', '1', '1495186113', '1', '2', '1495186132', '', '2', '5'],
            ['9', 'BX170519009', '1', 'test', '13112341234', '15', '5', '212', '', '122', null, null, null, '1495186516', null, null, null, null, null, null, null, '1495186517', null, null, '-2'],
            ['10', 'BX170519010', '1', 'test', '13112341234', '15', '5', '312', '', '12', null, null, null, '1495186524', '1495186537', '1', '1495186537', '1', '1495196683', '2', '2', null, null, null, '4'],
            ['11', 'BX170519011', '1', 'test', '13112341234', '15', '6', '123', '', '122', '5', '5', '5', '1495186531', '1495186571', '1', '1495186571', '1', '1495303744', '1', '2', '1495343619', '', '2', '5'],
            ['12', 'BX170519012', '1', 'test', '13112341234', '14', '5', '12', '', '122', null, null, null, '1495186561', '1495196679', '2', '1495196679', '2', null, null, '1', null, null, null, '3'],
            ['13', 'BX170519013', '2', 'test', '13112341234', '15', '4', '32', '', '22', null, null, null, '1495195865', '1495303728', '1', null, null, null, null, null, null, null, null, '2'],
            ['14', 'BX170519014', '2', 'test', '13112341234', '14', '5', '23', '', '232', null, null, null, '1495195872', '1495196673', '2', '1495196673', '2', null, null, '2', null, null, null, '3'],
            ['15', 'BX170519015', '2', 'test', '13112341234', '16', '6', '212', '', '222', null, null, null, '1495195880', '1495303728', '1', '1495303728', '1', null, null, '3', null, null, null, '3'],
            ['16', 'BX170519016', '2', 'test', '13112341234', '15', '7', '323', '', 'sds', null, null, null, '1495195890', '1495303728', '1', '1495303728', '1', '1495303744', '1', '1', null, null, null, '4'],
            ['17', 'BX170519017', '2', 'test', '13112341234', '15', '4', '212', '', 'ss', null, null, null, '1495196667', '1495303728', '1', null, null, null, null, null, null, null, null, '2'],
            ['18', 'BX170520001', '1', 'test', '13112341234', '14', '6', '34', '', '344', null, null, null, '1495219641', '1495303728', '1', '1495303728', '1', '1495303744', '1', '2', null, null, null, '4'],
            ['19', 'BX170520002', '1', 'test', '13112341234', '15', '4', '12', '', '122', null, null, null, '1495292544', '1495303728', '1', null, null, null, null, null, null, null, null, '2'],
            ['20', 'BX170520003', '1', 'test', '13112341234', '16', '9', '22', '', '22', null, null, null, '1495292552', '1495303728', '1', '1495303728', '1', null, null, '3', null, null, null, '3'],
            ['21', 'BX170520004', '1', 'test', '13112341234', '16', '10', '22', '', '1', null, null, null, '1495292562', null, null, null, null, null, null, null, null, null, null, '1'],
            ['22', 'BX170520005', '1', 'test', '13112341234', '17', '5', '22', '', '22', null, null, null, '1495295810', null, null, null, null, null, null, null, null, null, null, '1'],
            ['23', 'BX170520006', '1', 'test', '13112341234', '17', '6', '21', '', '12', null, null, null, '1495295817', '1495303728', '1', '1495303728', '1', '1495303744', '1', '2', null, null, null, '4'],
            ['24', 'BX170521001', '1', 'test', '13112341234', '16', '4', '33', '', 'dd', '5', '5', '5', '1495298256', '1495303728', '1', '1495303728', '1', '1495303744', '1', '3', '1495343623', '', '2', '5'],
            ['25', 'BX170521002', '1', 'test', '13112341234', '15', '9', '32', '', '233', null, null, null, '1495303678', null, null, null, null, null, null, null, null, null, null, '1'],
            ['26', 'BX170521003', '1', 'test', '13112341234', '17', '10', '32', '', 'dd', null, null, null, '1495303697', '1495303728', '1', '1495303728', '1', '1495303744', '1', '2', null, null, null, '4'],
            ['27', 'BX170521004', '1', 'test', '13112341234', '16', '10', '33', '', 'dd', null, null, null, '1495303707', '1495303728', '1', '1495303728', '1', null, null, '3', null, null, null, '3'],
            ['28', 'BX170521005', '1', 'test', '13112341234', '15', '5', '12', '', 'saa', null, null, null, '1495343639', null, null, null, null, null, null, null, null, null, null, '1'],
            ['29', 'BX170521006', '2', 'test', '13112341234', '15', '5', '21', '', '122', null, null, null, '1495343933', null, null, null, null, null, null, null, null, null, null, '1'],
            ['30', 'BX170521007', '2', 'test', '13112341234', '16', '6', '12', '', '12', null, null, null, '1495343942', null, null, null, null, null, null, null, null, null, null, '1'],
            ['31', 'BX170522001', '1', 'test', '13112341234', '15', '6', '121', '', '122', null, null, null, '1495420514', null, null, null, null, null, null, null, null, null, null, '1'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%repair_order}}');
    }

}
