<?php

use yii\db\Migration;

/**
 * Handles the creation of table `forum`.
 */
class m170404_110830_create_forum_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%forum}}';
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="楼苑表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'fup' => $this->integer(),
            'mark' => $this->integer(),
            'name' => $this->string(32)->notNull(),
            'sort_order' => $this->smallInteger(3)->notNull()->defaultValue(1),
            'fsort' => $this->smallInteger(3)->notNull()->defaultValue(1),
            'mold' => $this->smallInteger()->notNull()->defaultValue(1), //1-单间；2-套间
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[fup]]) REFERENCES {$table}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);

        //测试数据
        $this->batchInsert($table, ['id', 'fup', 'name', 'sort_order', 'fsort', 'mold', 'mark', 'stat'], [
            ['1', null, '桃苑', '1', '1', '2', '1', '1'],
            ['2', null, '李苑', '2', '2', '2', '2', '1'],
            ['3', null, '柳苑', '1', '1', '2', '3', '2'],
            ['4', '1', '22#', '1', '1', '2', '1', '1'],
            ['5', '1', '23#', '1', '1', '1', '1', '1'],
            ['6', '1', '24#', '1', '1', '2', '1', '1'],
            ['7', '2', '30#', '1', '2', '2', '2', '1'],
            ['9', '2', '33#', '1', '2', '2', '2', '1'],
            ['10', '2', '34#', '1', '2', '2', '2', '1'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%forum}}');
    }

}
