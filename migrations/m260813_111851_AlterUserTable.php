<?php

use yii\db\Migration;

class m260813_111851_AlterUserTable extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void {
        $this->addColumn('{{%user}}', 'data', $this->json()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void {
        $this->dropColumn('{{%user}}', 'data');
    }

}
