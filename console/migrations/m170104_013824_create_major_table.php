<?php

use yii\db\Migration;

/**
 * Handles the creation of table `major`.
 */
class m170104_013824_create_major_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%major}}';
        $collegeTable = '{{%college}}';
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="专业表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'sort_order' => $this->smallInteger(3)->notNull()->defaultValue(1),
            'college' => $this->integer()->notNull(),
            "FOREIGN KEY ([[college]]) REFERENCES {$collegeTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%major}}');
    }

}
