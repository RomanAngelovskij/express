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
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%xml_files%}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(250)->notNull(),
            'created_at' => $this->integer()
        ]);

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
