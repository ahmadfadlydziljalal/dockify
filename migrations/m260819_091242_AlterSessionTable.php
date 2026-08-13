<?php

use yii\base\NotSupportedException;
use yii\db\Migration;
use yii\helpers\ArrayHelper;

class m260819_091242_AlterSessionTable extends Migration {

    public function init(): void {
        $this->db = 'supportDb';
        parent::init();
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public function safeUp(): void {

        // check first if the columns already exist to avoid errors
        if (!$this->db->schema->getTableSchema('session', true)->getColumn('user_id')) {
            $this->addColumn('session', 'user_id', $this->integer()->null()->defaultValue(null));
        }

        // check if the column 'last_write' already exists to avoid errors
        if (!$this->db->schema->getTableSchema('session', true)->getColumn('last_write')) {
            $this->addColumn('session', 'last_write', $this->integer()->null()->defaultValue(null));
        }

        // check if the index already exists to avoid errors
        $uniqueIndex = $this->db->schema->findUniqueIndexes($this->db->schema->getTableSchema('session'));
        if (ArrayHelper::keyExists('idx_session_user_id', $uniqueIndex)) {
            $this->createIndex('idx_session_user_id', 'session', 'user_id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void {
        $this->dropIndex('idx_session_user_id', 'session');
        $this->dropColumn('session', 'user_id');
        $this->dropColumn('session', 'last_write');
    }

}
