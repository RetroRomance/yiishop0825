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
        <td>品名</td>
        <td>货号</td>
        <td>logo图</td>
        <td>品牌分类</td>
        <td>市场价格</td>
        <td>商场价格</td>
        <td>库存</td>
        <td>是否在售</td>
        <td>状态</td>
        <td>排序</td>
        <td>添加时间</td>
        <td>浏览次数</td>
        <td>操作</td>
    </tr>
    <?php foreach($model as $goods):?>
        <tr data-id="<?=$goods['id']?>">
            <td><?=$goods['name']?></td>
            <td><?=$goods['sn']?></td>
            <td><img src="<?=$goods['logo']?>" width="100px"></td>
            <td><?=$goods['brand_id']?></td>
            <td><?=$goods['market_price']?></td>
            <td><?=$goods['shop_price']?></td>
            <td><?=$goods['stock']?></td>
            <td>
            在售:<?=($goods['is_on_sale']) ==1 ? '✔': '' ?><br>
            下架:<?=($goods['is_on_sale']) ==0 ? '✔': '' ?>
            </td>
            <td>
                正常:<?=($goods['status']) ==1 ? '✔': '' ?><br>
                回收站:<?=($goods['status']) ==0 ? '✔': '' ?>
            </td>
            <td><?=$goods['sort']?></td>
            <td><?=$goods['create_time']?></td>
            <td><?=$goods['view_times']?></td>
            <td><?=\yii\bootstrap\Html::a('相册',['goods-gallery/index','id'=>$goods['id']],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$goods['id']],['class'=>'btn btn-warning'])?>
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