<?php

namespace frontend\models;

use common\models\XmlFiles;
use common\models\XmlFilesTags;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class XmlForm extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xml'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'XML File'
        ];
    }

    /**
     * @return bool
     */
    public function upload()
    {
        $uploadDir = rtrim(\Yii::$app->basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads';

        if (false === file_exists($uploadDir)) {
            FileHelper::createDirectory($uploadDir);
        }

        if ($this->validate()) {
            $fileName = $this->file->baseName . '.' . $this->file->extension;
            $filePath = $uploadDir . '/' . $fileName;

            if (false === $this->file->saveAs($filePath)) {
                $this->addError('file', "Upload file error");

                return false;
            }

            if (false === $this->_processXML($filePath, $fileName)) {
                $this->addError('file', "Process XML error");

                return false;
            }

            FileHelper::unlink($filePath);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $file Path to XML file
     * @param string $fileName Name of xml with extension
     * @return bool
     */
    protected function _processXML($file, $fileName)
    {
        $xmlContent = file_get_contents($file);

        if (false === $this->_validateXML($xmlContent)) {
            $this->addError('file', "It's not valid XML");

            return false;
        }

        $xmlModel = new XmlFiles();
        $xmlModel->name = $fileName;

        if (false === $xmlModel->save()) {
            $this->addError('file', "Can't save data about XML");

            return false;
        }

        $this->_processTags($xmlContent, $xmlModel->id);

        return true;
    }

    /**
     * Validate XML content
     * @param $xmlContent
     * @param string $version
     * @param string $encoding
     * @return bool
     */
    protected function _validateXML($xmlContent, $version = '1.0', $encoding = 'utf-8')
    {
        if (trim($xmlContent) == '') {
            return false;
        }

        libxml_use_internal_errors(true);

        $doc = new \DOMDocument($version, $encoding);
        $xml = $doc->loadXML($xmlContent);

        $errors = libxml_get_errors();
        libxml_clear_errors();

        return empty($errors);
    }

    /**
     * @param string $xmlContent
     * @param string $xmlFileId
     */
    protected function _processTags($xmlContent, $xmlFileId)
    {
        $tags = [];

        $elem = new \SimpleXMLElement($xmlContent);

        /** @var \SimpleXMLElement $node */
        foreach ($elem as $node) {
            $tags[$node->getName()]++;
            $children = $node->children();

            /** @var \SimpleXMLElement $child */
            foreach ($children as $child) {
                $tags[$child->getName()]++;
            }
        }

        if (!empty($tags)) {
            foreach ($tags as $tagName => $tagCount) {
                $xmlTagsModel = new XmlFilesTags();
                $xmlTagsModel->file_id = $xmlFileId;
                $xmlTagsModel->tag = $tagName;
                $xmlTagsModel->count = $tagCount;
                $xmlTagsModel->save();
            }
        }
    }
}