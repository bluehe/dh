<?php

use yii\db\Migration;

class m170417_050101_create_repair_unit extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%repair_unit}}';

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="维修单位表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'name' => $this->string(8)->notNull(),
            'company' => $this->string(64)->notNull(),
            'tel' => $this->string(64),
            'email' => $this->string(64),
            'address' => $this->string(),
            'note' => $this->string(),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
                ], $tableOptions);

        //测试数据
        $this->batchInsert($table, ['id', 'name', 'company', 'tel', 'email', 'address', 'note', 'stat'], [
            ['1', '东吴', '东吴物业', '', '', '', '', '1'],
            ['2', '华盈', '华盈物业', '', '', '', '', '1'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%repair_unit}}');
    }

}
