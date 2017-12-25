<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<table class="table">
    <tr>

        <td>logo图片</td>
        <td>操作</td>
    </tr>
    <?php foreach($model as $gg):?>
        <tr data-id="<?=$gg['id']?>">
            <td><img src="<?=$gg['path']?>"></td>
            <td>
                <a class="btn btn-danger" >删除</a> </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php

//===================加载文件
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    //依赖关系
    'depends'=>\yii\web\JqueryAsset::className()
]);

$upload_url = \yii\helpers\Url::to(['goods-gallery/upload','id'=>$id]);
$url = \yii\helpers\Url::to(['goods-gallery/del']);
$js =
    <<<js
// 初始化Web Uploader
var uploader = WebUploader.create({
    // 选完文件后，是否自动上传
    auto: true,

    // swf文件路径
    swf: 'webuploader/Uploader.swf',

    // 文件接收服务端。
    server: '$upload_url',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/gif,image/jpeg,image/png,image/jpg,image/bmp'
    }
});
// 文件上传成功，添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file,response ) {
    //给图片回显
    var html = '<tr><td><img src="'+response.url+'" ></td><td>' +
     '<a class="btn btn-danger" >删除</a></td></tr>';
    $("#table").last().append(html);
});
   //删除的ajax操作
    $('#table').on('click','.btn-danger',function(){
        console.debug(33333);
        var tr=$(this).closest('tr');
        $.get("$url",{id:tr.attr('data-id')},function(){
            tr.fadeOut();
        })
    })
js;
$this->registerJs($js);