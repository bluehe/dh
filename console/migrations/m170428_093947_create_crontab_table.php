<?php

use yii\db\Migration;

/**
 * Handles the creation of table `crontab`.
 */
class m170428_093947_create_crontab_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $table = '{{%crontab}}';
        $repair_order_table = '{{%repair_order}}';
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT="定时任务表"';
        }
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'start_at' => $this->integer()->notNull(),
            'end_at' => $this->integer(),
            'interval_time' => $this->integer(),
            'content' => $this->text()->notNull(),
            'exc_at' => $this->integer(),
            'stat' => $this->smallInteger()->notNull()->defaultValue(1),
                ], $tableOptions);

        //报修表定时任务
        $stat_e = \dms\models\RepairOrder::STAT_EVALUATE;
        $stat_r = \dms\models\RepairOrder::STAT_REPAIRED;
        $evaluate = \dms\models\RepairOrder::EVALUATE_VSAT;
//        $this->execute("set GLOBAL event_scheduler = ON;");
        $this->execute("DROP EVENT IF EXISTS `event_repairorder`;");
        $this->execute("CREATE EVENT `event_repairorder` ON SCHEDULE EVERY 1 DAY STARTS '2017-01-01 00:00:00'
            ENABLE
            DO
            BEGIN
                UPDATE {$repair_order_table} SET stat={$stat_e},evaluate={$evaluate},end_at=unix_timestamp(now()),note='系统默认' WHERE stat={$stat_r} AND repair_at<unix_timestamp(now())-3600*24*7;
            END");
        $this->insert($table, ['id' => 1, 'start_at' => 1483200000, 'end_at' => NULL, 'interval_time' => 86400, 'content' => " UPDATE {$repair_order_table} SET stat={$stat_e},evaluate={$evaluate},end_at=unix_timestamp(now()),note='系统默认' WHERE stat={$stat_r} AND repair_at<unix_timestamp(now())-3600*24*7", 'exc_at' => NULL, 'stat' => 1]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%crontab}}');
        $this->execute('DROP EVENT IF EXISTS `event_repairorder`;');
    }

}
