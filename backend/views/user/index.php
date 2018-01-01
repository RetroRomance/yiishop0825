<?php $form = \yii\bootstrap\ActiveForm::begin([]);
?>
<table class="table table-bordered">
    <tr>
        <td>编号</td>
        <td>姓名</td>
        <td>邮箱</td>
        <td>最后登录IP</td>
        <td>最后登录时间</td>
        <td>操作</td>
    </tr>
    <?php foreach($model as $user):?>
        <tr data-id="<?=$user['id']?>">
            <td><?=$user->id?></td>
            <td><?=$user->username?></td>
            <td><?=$user->email?></td>
            <td><?=$user->last_login_ip?></td>
            <td><?=$user->last_login_time?></td>
<!--            <td><img src="--><!--" width="100px"></td>-->
            <td><?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$user->id],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger'])?>
                <?=\yii\bootstrap\Html::a('添加用户角色',['user/add-user','id'=>$user->id],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('修改用户角色',['user/edit-user','id'=>$user->id],['class'=>'btn btn-warning'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="<?=\yii\helpers\Url::to(['user/add'])?>" class="btn btn-primary">添加</a>
<?php \yii\bootstrap\ActiveForm::end(); ?>
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
