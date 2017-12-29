<?php $form = \yii\bootstrap\ActiveForm::begin([]);
$this->registerCssFile('@web/dataTables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/dataTables/media/js/jquery.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJsFile('@web/dataTables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
?>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>权限</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model as $rbac):?>
        <tr data-id="<?=$rbac->name?>">
            <td><?=$rbac->name?></td>
            <td><?=$rbac->description?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$rbac->name],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::submitButton('删除',['class'=>'btn btn-danger'])?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="<?=\yii\helpers\Url::to(['rbac/add-permission'])?>" class="btn btn-primary">添加</a>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
$js=<<<JS
    $("table").on('click','.btn-danger',function(){
        var tr = $(this).closest('tr');
        if(confirm('确定删除？删除后不可恢复哦')){
            $.get("del-permission",{name:tr.attr('data-id')},function(){
                tr.fadeOut();
            });
        }
        return false;
    });
    $(document).ready( function () {
        $('#table_id_example').DataTable();
    } );
    $('#table_id_example').dataTable({
    "oLanguage": {
    "sLengthMenu": "每页显示 _MENU_ 条记录",
    "sZeroRecords": "对不起，查询不到任何相关数据",
    "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_条记录",
    "sInfoEmtpy": "找不到相关数据",
    "sInfoFiltered": "数据表中共为 _MAX_ 条记录)",
    "sProcessing": "正在加载中...",
    "sSearch": "搜索",
    "oPaginate": {
    "sFirst": "第一页",
    "sPrevious":" 上一页 ",
    "sNext": " 下一页 ",
    "sLast": " 最后一页 "
            },
        }
    });
    
JS;
$this->registerJs($js);
?>
