<?php

use yii\db\Migration;

class m260814_170100_InsertAuthItemRecords extends Migration
{
    /**
     * {@inheritdoc}
     *
     */
    public function safeUp(): void {
        $this->execute(<<<SQL
            INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
            ('/*', 2, NULL, NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
            ('/site/*', 2, NULL, NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
            ('super-admin', 1, NULL, NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
            ('user-default', 1, NULL, NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())
            ;
        SQL);

        $this->execute(<<<SQL
            INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
            ('super-admin', '/*'),
            ('user-default', '/site/*');
        SQL);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void {

        $this->execute(<<<SQL
            DELETE FROM `auth_item_child` WHERE `parent` IN ('super-admin', 'user-default');
        SQL);

        $this->execute(<<<SQL
            DELETE FROM `auth_item` WHERE `name` IN ('/*', '/currency/index', '/session/index', '/site/*', 'super-admin', 'user-default');
        SQL);
    }

}
