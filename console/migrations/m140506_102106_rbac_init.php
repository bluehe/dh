<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

/**
 * Initializes RBAC tables
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m140506_102106_rbac_init extends \yii\db\Migration {

    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager() {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }

    /**
     * @return bool
     */
    protected function isMSSQL() {
        return $this->db->driverName === 'mssql' || $this->db->driverName === 'sqlsrv' || $this->db->driverName === 'dblib';
    }

    /**
     * @inheritdoc
     */
    public function up() {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
                ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name)' .
            ($this->isMSSQL() ? '' : ' ON DELETE SET NULL ON UPDATE CASCADE'),
                ], $tableOptions);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name)' .
            ($this->isMSSQL() ? '' : ' ON DELETE CASCADE ON UPDATE CASCADE'),
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name)' .
            ($this->isMSSQL() ? '' : ' ON DELETE CASCADE ON UPDATE CASCADE'),
                ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
                ], $tableOptions);

        if ($this->isMSSQL()) {
            $this->execute("CREATE TRIGGER dbo.trigger_auth_item_child
            ON dbo.{$authManager->itemTable}
            INSTEAD OF DELETE, UPDATE
            AS
            DECLARE @old_name VARCHAR (64) = (SELECT name FROM deleted)
            DECLARE @new_name VARCHAR (64) = (SELECT name FROM inserted)
            BEGIN
            IF COLUMNS_UPDATED() > 0
                BEGIN
                    IF @old_name <> @new_name
                    BEGIN
                        ALTER TABLE auth_item_child NOCHECK CONSTRAINT FK__auth_item__child;
                        UPDATE auth_item_child SET child = @new_name WHERE child = @old_name;
                    END
                UPDATE auth_item
                SET name = (SELECT name FROM inserted),
                type = (SELECT type FROM inserted),
                description = (SELECT description FROM inserted),
                rule_name = (SELECT rule_name FROM inserted),
                data = (SELECT data FROM inserted),
                created_at = (SELECT created_at FROM inserted),
                updated_at = (SELECT updated_at FROM inserted)
                WHERE name IN (SELECT name FROM deleted)
                IF @old_name <> @new_name
                    BEGIN
                        ALTER TABLE auth_item_child CHECK CONSTRAINT FK__auth_item__child;
                    END
                END
                ELSE
                    BEGIN
                        DELETE FROM dbo.{$authManager->itemChildTable} WHERE parent IN (SELECT name FROM deleted) OR child IN (SELECT name FROM deleted);
                        DELETE FROM dbo.{$authManager->itemTable} WHERE name IN (SELECT name FROM deleted);
                    END
            END;");
        }

        //插入数据
        $this->batchInsert($authManager->itemTable, ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'], [
            ['/account/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/account/change-password', '2', null, null, null, '1482820123', '1482820123'],
            ['/account/index', '2', null, null, null, '1482820123', '1482820123'],
            ['/account/thumb', '2', null, null, null, '1482820123', '1482820123'],
            ['/common/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/common/college', '2', null, null, null, '1482820123', '1482820123'],
            ['/common/grade', '2', null, null, null, '1482820123', '1482820123'],
            ['/common/major', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/floor', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/forum', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/broom', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/captcha', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/index', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/smtp', '2', null, null, null, '1482820123', '1482820123'],
            ['admin', '1', '管理员', null, null, '1482820123', '1482820123'],
            ['employee', '1', '员工', null, null, '1482820123', '1482820123'],
            ['member', '1', '会员', null, null, '1482820123', '1482820123'],
            ['student', '1', '学生', null, null, '1482820123', '1482820123'],
            ['teacher', '1', '教师', null, null, '1482820123', '1482820123'],
            ['参数设置', '2', '参数设置', null, null, '1482820123', '1482820123'],
            ['楼苑设置', '2', '楼苑设置', null, null, '1482820123', '1482820123'],
            ['系统设置', '2', '系统设置', null, null, '1482820123', '1482820123'],
            ['账号信息', '2', '账号信息', null, null, '1482820123', '1482820123'],
        ]);
        $this->batchInsert($authManager->itemChildTable, ['parent', 'child'], [
            ['账号信息', '/account/*'],
            ['参数设置', '/common/*'],
            ['楼苑设置', '/forum/*'],
            ['系统设置', '/system/*'],
            ['admin', '参数设置'],
            ['admin', '楼苑设置'],
            ['admin', '系统设置'],
            ['admin', '账号信息'],
        ]);
        $this->batchInsert($authManager->assignmentTable, ['item_name', 'user_id', 'created_at'], [
            ['admin', '1', '1482481221'],
        ]);

//        $this->insert($authManager->itemTable, ['name' => '/account/*', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/account/change-password', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/account/index', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/account/thumb', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/common/*', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/common/college', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/common/grade', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/common/major', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/system/*', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/system/captcha', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/system/index', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/system/smtp', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/forum/*', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/forum/floor', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '/forum/forum', 'type' => '2', 'description' => NULL, 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => 'admin', 'type' => '1', 'description' => '管理员', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => 'employee', 'type' => '1', 'description' => '员工', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => 'member', 'type' => '1', 'description' => '会员', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => 'student', 'type' => '1', 'description' => '学生', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => 'teacher', 'type' => '1', 'description' => '教师', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '参数设置', 'type' => '2', 'description' => '参数设置', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '系统设置', 'type' => '2', 'description' => '系统设置', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '账号信息', 'type' => '2', 'description' => '账号信息', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemTable, ['name' => '楼苑设置', 'type' => '2', 'description' => '楼苑设置', 'rule_name' => NULL, 'data' => NULL, 'created_at' => '1482820123', 'updated_at' => '1482820123']);
//        $this->insert($authManager->itemChildTable, ['parent' => '账号信息', 'child' => '/account/*']);
//        $this->insert($authManager->itemChildTable, ['parent' => '参数设置', 'child' => '/common/*']);
//        $this->insert($authManager->itemChildTable, ['parent' => '系统设置', 'child' => '/system/*']);
//        $this->insert($authManager->itemChildTable, ['parent' => '楼苑设置', 'child' => '/forum/*']);
//        $this->insert($authManager->itemChildTable, ['parent' => 'admin', 'child' => '参数设置']);
//        $this->insert($authManager->itemChildTable, ['parent' => 'admin', 'child' => '系统设置']);
//        $this->insert($authManager->itemChildTable, ['parent' => 'admin', 'child' => '账号信息']);
//        $this->insert($authManager->itemChildTable, ['parent' => 'admin', 'child' => '楼苑设置']);
//
//        $this->insert($authManager->assignmentTable, ['item_name' => 'admin', 'user_id' => '1', 'created_at' => '1482481221']);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        if ($this->isMSSQL()) {
            $this->execute('DROP TRIGGER dbo.trigger_auth_item_child;');
        }

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }

}
