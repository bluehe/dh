<?php

use yii\db\Migration;

/**
 * Handles the creation of table `parameter`.
 */
class m170104_072048_create_parameter_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%parameter}}';
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="参数表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull(),
            'v' => $this->text()->notNull(),
            'sort_order' => $this->smallInteger(3)->notNull()->defaultValue(1),
                ], $tableOptions);

        //测试数据
        $this->batchInsert($table, ['id', 'name', 'sort_order', 'v'], [
            ['1', 'grade', '1', '大一'],
            ['2', 'grade', '2', '大二'],
            ['3', 'grade', '3', '大三'],
            ['4', 'grade', '4', '大四'],
            ['5', 'floor', '1', '一层'],
            ['6', 'floor', '2', '二层'],
            ['7', 'floor', '3', '三层'],
            ['8', 'floor', '4', '四层'],
            ['9', 'floor', '5', '五层'],
            ['10', 'floor', '101', '一单元'],
            ['11', 'floor', '102', '二单元'],
            ['12', 'floor', '103', '三单元'],
            ['13', 'floor', '6', '六层'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%parameter}}');
    }

}
