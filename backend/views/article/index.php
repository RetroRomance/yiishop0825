<?php $form = \yii\bootstrap\ActiveForm::begin([]);
?>
<table class="table table-bordered">
    <tr>

        <td>品名</td>
        <td>简介</td>
        <td>分类id</td>
        <td>排序</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php foreach($model as $at):?>
        <tr data-id="<?=$at['id']?>">
            <td><?=$at['name']?></td>
            <td><?=$at['intro']?></td>
            <td><?=$at['article_category_id']?></td>
            <td><?=$at['sort']?></td>
            <td>
            隐藏:<?=($at['status']) ==0 ? '✔': '' ?><br>
            显示:<?=($at['status']) ==1 ? '✔': '' ?>
            </td>
            <td><?=$at['create_time']?></td>
            <td><?=\yii\bootstrap\Html::a('查看',['article/show','id'=>$at['id']],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$at['id']],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger'])?></td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-primary">添加</a>
<?php \yii\bootstrap\ActiveForm::end();?>
<?php
$js=<<<JS
    $("table").on('click','.btn-danger',function(){
        var tr = $(this).closest('tr');
        if(confirm('确定删除？删除后不可恢复哦')){
            $.get("del",{id:tr.attr('data-id')},function(){
                tr.fadeOut();
            });
        }
        return false;
    });
JS;
$this->registerJs($js);
?>