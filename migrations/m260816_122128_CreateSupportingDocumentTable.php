<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supporting_document}}`.
 */
class m260816_122128_CreateSupportingDocumentTable extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void {
        $this->createTable('{{%supporting_document}}', [
            'id'             => $this->primaryKey(),
            'file_name'      => $this->string()->notNull(),
            'file_extension' => $this->string()->notNull(),
            'file_path'      => $this->text()->notNull(),
            'version'        => $this->integer()->notNull()->defaultValue(1),
            'is_active'      => $this->boolean()->notNull()->defaultValue(true),
            'environment'    => $this->string()->notNull()->defaultValue('development'),
            'created_at'     => $this->integer()->notNull(),
            'updated_at'     => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void {
        $this->dropTable('{{%supporting_document}}');
    }
}
