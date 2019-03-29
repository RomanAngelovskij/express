<?php
namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class XmlFilesTags
 * @package common\models
 *
 * @property integer $id
 * @property integer $file_id
 * @property string $tag
 * @property integer $count
 *
 * @property XmlFiles $file
 */
class XmlFilesTags extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%xml_files_tags%}}';
    }

    public function rules()
    {
        return [
            ['file_id', 'exist', 'targetClass' => XmlFiles::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file_id' => 'Uploaded XML file id',
            'tag' => 'Tag name',
            'count' => 'Number of tag in XML',
            'file' => 'XML file'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(XmlFiles::class, ['id' => 'file_id']);
    }
}