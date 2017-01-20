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
            'k' => $this->string(32)->notNull(),
            'v' => $this->text()->notNull(),
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%parameter}}');
    }

}
