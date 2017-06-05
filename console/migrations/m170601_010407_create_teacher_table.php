<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

/**
 * Handles the creation of table `teacher`.
 */
class m170601_010407_create_teacher_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%teacher}}';
        $collegeTable = '{{%college}}';
        $userTable = Configs::instance()->userTable;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="教职工表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'uid' => $this->integer(),
            'name' => $this->string(8)->notNull(),
            'gender' => $this->string(6),
            'college' => $this->integer(),
            'tel' => $this->string(64),
            'email' => $this->string(64),
            'address' => $this->string(),
            'note' => $this->string(),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[college]]) REFERENCES {$collegeTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);

        //测试数据
//        $this->batchInsert($table, ['id', 'uid', 'name', 'gender', 'college', 'tel', 'email', 'address', 'note', 'stat'], [
//        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%teacher}}');
    }

}
