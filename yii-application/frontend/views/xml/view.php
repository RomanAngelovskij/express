<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\XmlFiles $file
 */

$this->title = $file->name;
?>

<h1><?= $file->name ?> от <?= date('d.m.Y H:i', $file->created_at) ?></h1>

<?php if (!empty($file->tags)): ?>
    <table class="table table-striped"?>
        <thead>
            <th>Tag</th>
            <th>Count</th>
        </thead>
    <?php foreach ($file->tags as $tag): ?>
        <tr>
            <td><?= $tag->tag?></td>
            <td><?= $tag->count?></td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>
