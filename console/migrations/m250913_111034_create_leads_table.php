<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%leads}}`.
 */
class m250913_111034_create_leads_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%leads}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->comment('Название заказа'),
            'deadline' => $this->dateTime()->notNull()->comment('Срок выполнения'),
            'translator_id' => $this->integer()->null()->comment('Переводчик'),
            'status' => "ENUM('new','in_progress','done') NOT NULL DEFAULT 'new'",
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            '{{%fk-leads-translator_id}}',
            '{{%leads}}',
            'translator_id',
            '{{%translator}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-leads-translator_id}}',
            '{{%leads}}',
            'translator_id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-leads-translator_id}}', '{{%leads}}');
        $this->dropIndex('{{%idx-leads-translator_id}}', '{{%leads}}');

        $this->dropTable('{{%leads}}');
    }
}
