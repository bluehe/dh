<?php

use yii\db\Migration;

/**
 * Handles the creation of table `college`.
 */
class m170103_051924_create_college_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%college}}';
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="学院表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'sort_order' => $this->smallInteger(3)->notNull()->defaultValue(1),
                ], $tableOptions);

        //测试数据
        $this->batchInsert($table, ['id', 'name', 'sort_order'], [
            ['1', '经济学院', '5'],
            ['2', '管理学院', '2'],
            ['3', '通信学院', '3'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%college}}');
    }

}
