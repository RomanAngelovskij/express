<?php
namespace frontend\controllers;

use common\models\XmlFiles;
use frontend\models\search\XmlFilesSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use frontend\models\XmlForm;
use yii\web\Controller;

class XmlController extends Controller
{
    public function actionIndex()
    {
        $overflowedCount = Yii::$app->params['overflowedCount'] ?: 20;

        $model = new XmlForm();
        $searchModel = new XmlFilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
                Yii::$app->session->setFlash('success', "File upload successful");
            }
        }

        $tagsOverflowedFiles = Yii::$app->db->createCommand('
            SELECT files.name, files.id, SUM(tags.count) AS cnt FROM xml_files as files
            LEFT JOIN xml_files_tags tags ON tags.file_id = files.id
            GROUP BY tags.file_id
            HAVING cnt > :overflowedCount
        ')
            ->bindValue(':overflowedCount', $overflowedCount)
            ->queryAll();

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tagsOverflowedFiles' => $tagsOverflowedFiles,
        ]);
    }

    public function actionView($id)
    {
        $file = XmlFiles::findOne($id);

        if (empty($file)) {
            throw new NotFoundHttpException('XML not found');
        }

        return $this->render('view', [
           'file' => $file
        ]);
    }
}