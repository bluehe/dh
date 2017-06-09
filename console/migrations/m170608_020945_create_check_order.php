<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

class m170608_020945_create_check_order extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%check_order}}';
        $bedTable = '{{%bed}}';

        $userTable = Configs::instance()->userTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="入住记录表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'related_table' => $this->string()->notNull(),
            'related_id' => $this->integer()->notNull(),
            'bed' => $this->integer()->notNull(),
            'note' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'checkout_at' => $this->integer(),
            'created_uid' => $this->integer(),
            'updated_uid' => $this->integer(),
            'checkout_uid' => $this->integer(),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
            "FOREIGN KEY ([[created_uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[updated_uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[checkout_uid]]) REFERENCES {$userTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
            "FOREIGN KEY ([[bed]]) REFERENCES {$bedTable}([[id]]) ON DELETE CASCADE ON UPDATE CASCADE",
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%check_order}}');
    }

}
