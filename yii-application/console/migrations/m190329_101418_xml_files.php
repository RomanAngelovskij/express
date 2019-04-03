<?php

use yii\db\Migration;

/**
 * Class m190329_101418_xml_files
 */
class m190329_101418_xml_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable('{{%xml_files%}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250)->notNull(),
            'created_at' => $this->integer()
        ], $tableOptions);

        $this->createIndex('xml_files__names', '{{%xml_files%}}', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%xml_files%}}');
    }
}
