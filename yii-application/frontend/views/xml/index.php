<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var ActiveForm $form
 * @var \frontend\models\XmlForm $model
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \frontend\models\search\XmlFilesSearch $searchModel
 * @var \common\models\XmlFiles[] $tagsOverflowedFiles
 */
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]) ?>

    <?= $form->field($model, 'file')->fileInput(); ?>

    <?= \yii\helpers\Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end() ?>


<div class="row col-md-12">
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                // you may configure additional properties here
            ],
            [
                'attribute' => 'created_at',
                'value' => function($data) {
                    return date ('d.m.Y H:i', $data->created_at);
                }
            ],
            [
                'attribute' => 'name',
                'content' => function($data){
                    return Html::a($data->name, 'xml/view?id=' . $data->id, ['target' => '_blank']);
                }
            ],
        ]
    ])
    ?>
</div>

<?php if (!empty($tagsOverflowedFiles)): ?>
    <table class="table table-striped">
        <h3>Overflowed XML</h3>
        <?php foreach ($tagsOverflowedFiles as $file): ?>
            <tr>
                <td><?= Html::a($file['name'], 'xml/view?id=' . $file['id'], ['target' => '_blank']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
