<?php $form = \yii\bootstrap\ActiveForm::begin([]);
?>
    <table class="table table-bordered">
        <tr>

            <td>文章id</td>
            <td>内容</td>

        </tr>
        <?php foreach($model as $at):?>
            <tr>
                <td><?=$at['article_id']?></td>
                <td><?=$at['content']?></td>

            </tr>
        <?php endforeach; ?>
    </table>
    <a href="<?=\yii\helpers\Url::to(['article/index'])?>" class="btn btn-primary">返回</a>
<?php \yii\bootstrap\ActiveForm::end();?>