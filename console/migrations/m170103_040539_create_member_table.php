<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

/**
 * Handles the creation of table `member`.
 */
class m170103_040539_create_member_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%member}}';
        $userTable = Configs::instance()->userTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="会员表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'uid' => $this->integer()->notNull(),
            'name' => $this->string(10),
            'gender' => $this->string(2),
            'bday' => $this->date(),
            'location' => $this->string(255),
            'tel' => $this->string(20),
            'qq' => $this->string(13),
            'stat' => $this->smallInteger()->notNull()->defaultValue(10),
            "FOREIGN KEY ([[uid]]) REFERENCES {$userTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%member}}');
    }

}
