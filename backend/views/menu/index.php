<?php $form = \yii\bootstrap\ActiveForm::begin([]);

?>
<!--<form>-->
<!--    <input type="text" name="name" id="name" value="" placeholder="品名"/> <input type="text" name="sn" id="sn" value="" placeholder="货号"/>  <a href="--><?//=\yii\helpers\Url::to(['goods/index'])?><!--" class="btn btn-primary">搜索</a>-->
<!--</form>-->
<form action="goods/index" method="get">
    <input type="text" name="name" id="name" value="" placeholder="品名"/> <input type="text" name="sn" id="sn" value="" placeholder="货号"/><input  type="submit" class="btn btn-primary btn-sm" value="搜索">
</form>
<table class="table table-bordered">
    <tr>
        <td>id</td>
        <td>名称</td>
        <td>所属菜单</td>
        <td>路由/地址</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    <?php foreach($model as $menu):?>
        <tr data-id="<?=$menu['id']?>">
            <td><?=$menu['id']?></td>
            <td><?=$menu['name']?></td>
            <td><?=$menu['parent_id']?></td>
            <td><?=$menu['url']?></td>
            <td><?=$menu['sn']?></td>
            <td><?=\yii\bootstrap\Html::a('相册',['goods-gallery/index','id'=>$menu['id']],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$menu['id']],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger'])?></td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="<?=\yii\helpers\Url::to(['goods/add'])?>" class="btn btn-primary">添加</a>
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