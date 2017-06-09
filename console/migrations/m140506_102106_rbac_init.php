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
            ['/system/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/captcha', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/index', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/smtp', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/business', '2', null, null, null, '1482820123', '1482820123'],
            ['/system/crontab', '2', null, null, null, '1482820123', '1482820123'],
            ['/college/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/college/college', '2', null, null, null, '1482820123', '1482820123'],
            ['/college/grade', '2', null, null, null, '1482820123', '1482820123'],
            ['/college/major', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/floor', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/forum', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/room', '2', null, null, null, '1482820123', '1482820123'],
            ['/forum/bed', '2', null, null, null, '1482820123', '1482820123'],
            ['/repair/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/repair/type', '2', null, null, null, '1482820123', '1482820123'],
            ['/repair/unit', '2', null, null, null, '1482820123', '1482820123'],
            ['/repair/worker', '2', null, null, null, '1482820123', '1482820123'],
            ['/business/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/business/repair-business', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-work', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-export', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-view', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-repair', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-repairs', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-update', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-accept', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-accepts', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/repair-dispatch', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/building', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/teacher', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/teacher-create', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/teacher-update', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/teacher-delete', '2', null, null, null, '1482820123', '1482820123'],
            ['/work/teacher-bind', '2', null, null, null, '1482820123', '1482820123'],
            ['/student/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/student/student', '2', null, null, null, '1482820123', '1482820123'],
            ['/statistics/*', '2', null, null, null, '1482820123', '1482820123'],
            ['/statistics/repair', '2', null, null, null, '1482820123', '1482820123'],
            ['admin', '1', '超级管理员', null, null, '1482820123', '1482820123'],
            ['member', '1', '会员', null, null, '1482820123', '1482820123'],
            ['employee', '1', '员工', null, null, '1482820123', '1482820123'],
            ['student', '1', '学生', null, null, '1482820123', '1482820123'],
            ['teacher', '1', '教职工', null, null, '1482820123', '1482820123'],
            ['manager', '1', '楼苑管理', null, null, '1482820123', '1482820123'],
            ['repair_worker', '1', '维修工', null, null, '1482820123', '1482820123'],
            ['repair_admin', '1', '报修审核', null, null, '1482820123', '1482820123'],
            ['guest', '1', '游客', null, null, '1482820123', '1482820123'],
            ['账号信息', '2', '账号信息', null, null, '1482820123', '1482820123'],
            ['业务中心', '2', '业务中心', null, null, '1482820123', '1482820123'],
            ['日常事务', '2', '日常事务', null, null, '1482820123', '1482820123'],
            ['报修管理', '2', '全部报修管理权限', null, null, '1482820123', '1482820123'],
            ['维修管理', '2', '维修工管理权限', null, null, '1482820123', '1482820123'],
            ['楼苑概况', '2', '楼苑概况', null, null, '1482820123', '1482820123'],
            ['教职工管理', '2', '教职工管理', null, null, '1482820123', '1482820123'],
            ['学生管理', '2', '学生管理', null, null, '1482820123', '1482820123'],
            ['系统设置', '2', '系统设置', null, null, '1482820123', '1482820123'],
            ['学院设置', '2', '学院设置', null, null, '1482820123', '1482820123'],
            ['楼苑设置', '2', '楼苑设置', null, null, '1482820123', '1482820123'],
            ['维修设置', '2', '维修设置', null, null, '1482820123', '1482820123'],
            ['数据统计', '2', '数据统计', null, null, '1482820123', '1482820123'],
            ['报修统计', '2', '报修统计', null, null, '1482820123', '1482820123'],
        ]);
        $this->batchInsert($authManager->itemChildTable, ['parent', 'child'], [
            ['账号信息', '/account/*'],
            ['业务中心', '/business/*'],
            //['日常事务', '/work/*'],
            ['日常事务', '报修管理'],
            ['日常事务', '楼苑概况'],
            ['日常事务', '教职工管理'],
            ['日常事务', '学生管理'],
            //['日常事务', '报修派工'],
            ['维修管理', '/work/repair-work'],
            ['维修管理', '/work/repair-export'],
            ['维修管理', '/work/repair-view'],
            ['维修管理', '/work/repair-repair'],
            ['维修管理', '/work/repair-repairs'],
            ['报修管理', '维修管理'],
            ['报修管理', '/work/repair-update'],
            ['报修管理', '/work/repair-accept'],
            ['报修管理', '/work/repair-accepts'],
            ['报修管理', '/work/repair-dispatch'],
            ['楼苑概况', '/work/building'],
            ['教职工管理', '/work/teacher'],
            ['教职工管理', '/work/teacher-create'],
            ['教职工管理', '/work/teacher-update'],
            ['教职工管理', '/work/teacher-delete'],
            ['教职工管理', '/work/teacher-bind'],
            ['学生管理', '/student/*'],
            ['系统设置', '/system/*'],
            ['学院设置', '/college/*'],
            ['楼苑设置', '/forum/*'],
            ['维修设置', '/repair/*'],
            ['数据统计', '/statistics/*'],
            ['报修统计', '/statistics/repair'],
            ['admin', '系统设置'],
            ['admin', '学院设置'],
            ['admin', '楼苑设置'],
            ['admin', '维修设置'],
            ['admin', '日常事务'],
            ['admin', '数据统计'],
            ['repair_worker', '报修统计'],
            ['repair_admin', '报修统计'],
            ['repair_worker', '维修管理'],
            ['repair_admin', '报修管理'],
            ['member', '账号信息'],
            ['member', '业务中心'],
        ]);
        $this->batchInsert($authManager->assignmentTable, ['item_name', 'user_id', 'created_at'], [
            ['admin', '1', '1482481221'],
            ['repair_worker', '2', '1482481221'],
            ['repair_admin', '2', '1482481221'],
            ['member', '1', '1482481221'],
            ['member', '2', '1482481221'],
        ]);
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
