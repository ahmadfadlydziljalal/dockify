<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth}}`.
 */
class m260813_090045_CreateAuthTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void {
        $this->execute(<<<SQL
            create table auth
            (
                id        int auto_increment primary key,
                user_id   int          not null,
                source    varchar(255) not null,
                source_id varchar(255) not null,
                constraint `fk-auth-user_id-user-id`
                foreign key (user_id) references user (id)
                on update cascade on delete cascade
            );

            SQL
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%auth}}');
    }
}
