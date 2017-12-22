<?php $form = \yii\bootstrap\ActiveForm::begin([]);
?>
<table class="table table-bordered">
    <tr>

        <td>品名</td>
        <td>简介</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach($model as $ac):?>
        <tr data-id="<?=$ac['id']?>">
            <td><?=$ac['name']?></td>
            <td><?=$ac['intro']?></td>
            <td><?=$ac['sort']?></td>
            <td>
            隐藏:<?=($ac['status']) ==0 ? '✔': '' ?><br>
            显示:<?=($ac['status']) ==1 ? '✔': '' ?>
            </td>
            <td><?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$ac['id']],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger'])?></td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-primary">添加</a>
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