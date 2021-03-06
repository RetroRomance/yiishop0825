<?php $form = \yii\bootstrap\ActiveForm::begin([]);
?>
<table class="table table-bordered">
    <tr>

        <td>角色</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach($model as $rbac):?>
        <tr data-id="<?=$rbac->name?>">
            <td><?=$rbac->name?></td>
            <td><?=$rbac->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$rbac->name],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger'])?></td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="<?=\yii\helpers\Url::to(['rbac/add-role'])?>" class="btn btn-primary">添加</a>
<?php \yii\bootstrap\ActiveForm::end();?>
<?php
$js=<<<JS
    $("table").on('click','.btn-danger',function(){
        var tr = $(this).closest('tr');
        if(confirm('确定删除？删除后不可恢复哦')){
            $.get("del-role",{name:tr.attr('data-id')},function(){
                tr.fadeOut();
            });
        }
        return false;
    });
JS;
$this->registerJs($js);
?>