<?php $form = \yii\bootstrap\ActiveForm::begin([]);
?>
<table class="table table-bordered">
    <tr>
        <td>树id</td>
        <td>左值</td>
        <td>右值</td>
        <td>层级</td>
        <td>上级分类id</td>
        <td>名称</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach($model as $gc):?>
        <tr data-id="<?=$gc['id']?>">
            <td><?=$gc['tree']?></td>
            <td><?=$gc['lft']?></td>
            <td><?=$gc['rgt']?></td>
            <td><?=$gc['depth']?></td>
            <td><?=$gc['parent_id']?></td>
            <td><?=$gc['name']?></td>
            <td><?=$gc['intro']?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$gc['id']],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger'])?></td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-primary">添加</a>
<?php \yii\bootstrap\ActiveForm::end();?>
<?php
$js=<<<JS
    $("table").on('click','.btn-danger',function(){
        var tr = $(this).closest('tr');
        if(confirm('确定删除？删除后不可恢复哦')){
            $.get("del",{id:tr.attr('data-id')},function(){
                console.debug('aaa');
                tr.fadeOut();
            });
        }
        return false;
    });
JS;
$this->registerJs($js);
?>