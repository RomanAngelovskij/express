<?php

use yii\db\Migration;

/**
 * Class m190329_101433_xml_files_tags
 */
class m190329_101433_xml_files_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable('{{%xml_files_tags%}}', [
            'id' => $this->primaryKey(),
            'file_id' => $this->integer()->notNull(),
            'tag' => $this->string(),
            'count' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('xml_files_tags__file_id', '{{%xml_files_tags%}}', 'file_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%xml_files_tags%}}');
    }
}
