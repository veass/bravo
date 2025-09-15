<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%translator}}`.
 */
class m250913_093150_create_translator_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%translator}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'availability' => "ENUM('weekday', 'flexible') NOT NULL DEFAULT 'weekday'",
            'status' => "ENUM('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'общий статус: работает переводчик или нет'",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%translator}}');
    }
}
