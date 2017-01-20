<?php

use mdm\admin\components\Configs;

/**
 * Migration table of table_menu
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class m140602_111327_create_menu_table extends \yii\db\Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $menuTable = Configs::instance()->menuTable;
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($menuTable, [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'parent' => $this->integer(),
            'route' => $this->string(),
            'order' => $this->integer(),
            'data' => $this->binary(),
            "FOREIGN KEY ([[parent]]) REFERENCES {$menuTable}([[id]]) ON DELETE SET NULL ON UPDATE CASCADE",
                ], $tableOptions);

        //插入数据
//        $this->insert($menuTable, ['id' => 1, 'name' => '系统设置', 'parent' => NULL, 'route' => '/system/index', 'order' => 1, 'data' => 0x7B2269636F6E223A2266612066612D636F6773227D]);
//        $this->insert($menuTable, ['id' => 2, 'name' => '系统信息', 'parent' => 1, 'route' => '/system/index', 'order' => 1, 'data' => 0x7B2269636F6E223A2266612066612D636F67227D]);
//        $this->insert($menuTable, ['id' => 3, 'name' => '邮件设置', 'parent' => 1, 'route' => '/system/smtp', 'order' => 2, 'data' => 0x7B2269636F6E223A2266612066612D656E76656C6F70652D6F227D]);
//        $this->insert($menuTable, ['id' => 4, 'name' => '验证码设置', 'parent' => 1, 'route' => '/system/captcha', 'order' => 3, 'data' => 0x7B2269636F6E223A2266612066612D636865636B2D7371756172652D6F227D]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable(Configs::instance()->menuTable);
    }

}
