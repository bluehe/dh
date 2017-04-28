<?php

use yii\db\Migration;
use mdm\admin\components\Configs;

class m160312_050000_create_user extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $userTable = Configs::instance()->userTable;
        $db = Configs::userDb();
        $itemTable = Yii::$app->getAuthManager()->itemTable;

        // Check if the table exists
        if ($db->schema->getTableSchema($userTable, true) === null) {
            $this->createTable($userTable, [
                'id' => $this->primaryKey(),
                'username' => $this->string(32)->notNull(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string(),
                'email' => $this->string()->notNull(),
                'avatar' => $this->string()->notNull(),
                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                    ], $tableOptions);
            //插入数据
            $this->insert($userTable, ['id' => 1, 'username' => 'admin', 'auth_key' => 'BXVEZBxBX8IfnV2ZvZteYALorDvJ7JK3', 'password_hash' => '$2y$13$vmgY.lIfJs2KkjJUnXFDcOzbxvByXmuUbsIkC2E9MSCfKAb08E7qO', 'password_reset_token' => NULL, 'email' => '179611207@qq.com', 'avatar' => '', 'status' => 10, 'created_at' => 1482391032, 'updated_at' => 1485054242]);
            $this->insert($userTable, ['id' => 2, 'username' => 'admin1', 'auth_key' => 'gLduEXJ2UjMg5_OGqSx0kAnFv0owHat0', 'password_hash' => '$2y$13$qG1IhRXFL97/UnPP1YVzL.RRmwANK7zJF8Tf41GNZ6VF5dNxokTjO', 'password_reset_token' => NULL, 'email' => '1796112071@qq.com', 'avatar' => '', 'status' => 10, 'created_at' => 1482391032, 'updated_at' => 1485054242]);
        }
    }

    public function down() {
        $userTable = Configs::instance()->userTable;
        $db = Configs::userDb();
        if ($db->schema->getTableSchema($userTable, true) !== null) {
            $this->dropTable($userTable);
        }
    }

}
